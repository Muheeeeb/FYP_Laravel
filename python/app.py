from flask import Flask, request, jsonify
import PyPDF2 as pdf
import json
import os
import logging
import time
from logging.handlers import RotatingFileHandler
import openai  # Changed back to basic import
from flask_cors import CORS

# Initialize the Flask app
app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

# Configure logging
logging.basicConfig(level=logging.INFO)
handler = RotatingFileHandler('app.log', maxBytes=10000, backupCount=3)
handler.setFormatter(logging.Formatter(
    '[%(asctime)s] %(levelname)s: %(message)s'
))
app.logger.addHandler(handler)

# Configure OpenAI API
API_KEY = os.getenv('OPENAI_API_KEY')
if not API_KEY:
    try:
        # Try to load from .env file if python-dotenv is available
        from dotenv import load_dotenv
        load_dotenv()
        API_KEY = os.getenv('OPENAI_API_KEY')
    except ImportError:
        pass

if not API_KEY:
    raise ValueError(
        "OpenAI API key not found. Please set the OPENAI_API_KEY environment variable "
        "or create a .env file with your API key."
    )

try:
    # Set the API key directly
    openai.api_key = API_KEY
    
    # Test the API key by making a small request
    response = openai.ChatCompletion.create(
        model="gpt-4",
        messages=[{"role": "user", "content": "test"}],
        max_tokens=5
    )
    app.logger.info("Successfully authenticated with OpenAI API")
except Exception as e:
    app.logger.error(f"Error initializing OpenAI client: {str(e)}")
    raise ValueError(f"Failed to initialize OpenAI client: {str(e)}")

def get_openai_response(input_text, retries=3, delay=1):
    """Function to interact with the OpenAI API with retries"""
    for attempt in range(retries):
        try:
            app.logger.info(f"Attempt {attempt + 1} to get OpenAI response")
            
            try:
                # Generate content using GPT-4
                response = openai.ChatCompletion.create(
                    model="gpt-4",
                    messages=[
                        {
                            "role": "system",
                            "content": "You are an expert ATS (Applicant Tracking System) that evaluates resumes against job descriptions. Provide responses in JSON format only."
                        },
                        {
                            "role": "user",
                            "content": input_text
                        }
                    ],
                    temperature=0.7,
                    max_tokens=2048
                )
                
                if response and response.choices and response.choices[0].message.content:
                    # Extract the response text
                    json_str = response.choices[0].message.content
                    
                    # Find JSON content between curly braces if present
                    start_idx = json_str.find('{')
                    end_idx = json_str.rfind('}') + 1
                    if start_idx != -1 and end_idx != -1:
                        json_str = json_str[start_idx:end_idx]
                    
                    # Test if it's valid JSON
                    json.loads(json_str)
                    app.logger.info("Successfully received and validated OpenAI response")
                    return json_str
                    
                app.logger.warning("Empty or invalid response from OpenAI API")
                
            except Exception as api_error:
                app.logger.error(f"API call error: {str(api_error)}")
                if attempt < retries - 1:
                    time.sleep(delay)
                    delay *= 2
                continue
                
        except Exception as e:
            app.logger.error(f"Error in OpenAI API setup (attempt {attempt + 1}): {str(e)}")
            if attempt < retries - 1:
                time.sleep(delay)
                delay *= 2
            continue
            
    # If all retries failed, return a default response
    return json.dumps({
        "JD Match": "0%",
        "MissingKeywords": ["Analysis failed"],
        "Profile Summary": "Could not analyze the resume"
    })

def extract_pdf_text(uploaded_file):
    """Function to extract text from a PDF file"""
    try:
        app.logger.info(f"Extracting text from PDF: {uploaded_file.filename}")
        reader = pdf.PdfReader(uploaded_file)
        text = ""
        for page in reader.pages:
            text += page.extract_text() or ""
        if text:
            app.logger.info(f"Successfully extracted text from {uploaded_file.filename}")
            return text
        app.logger.warning(f"No text extracted from {uploaded_file.filename}")
        return ""
    except Exception as e:
        app.logger.error(f"Error extracting PDF text from {uploaded_file.filename}: {str(e)}")
        return ""

# Prompt template for the AI
input_prompt = """
Act as a skilled ATS (Applicant Tracking System) with expertise in tech field, 
software engineering, data science, data analysis, and big data engineering. 
Evaluate the resume against the given job description. Consider the job market 
is very competitive and provide accurate evaluation.

Resume: {text}
Job Description: {jd}

Provide the response in the following JSON format only:
{{"JD Match": "percentage%", "MissingKeywords": ["keyword1", "keyword2"], "Profile Summary": "brief summary"}}
"""

@app.route('/evaluate_resume', methods=['POST'])
def evaluate_resume():
    """Endpoint to evaluate resumes against a job description"""
    try:
        app.logger.info("Received resume evaluation request")
        app.logger.info(f"Form data: {request.form}")
        app.logger.info(f"Files: {[f.filename for f in request.files.getlist('resumes')]}")
        
        # Validate request
        if 'job_description' not in request.form:
            app.logger.error("No job description provided")
            return jsonify({"error": "Job description is required"}), 400
            
        if 'resumes' not in request.files:
            app.logger.error("No resume files provided")
            return jsonify({"error": "Resume file is required"}), 400

        jd = request.form['job_description']
        app.logger.info(f"Job Description: {jd[:100]}...") # Log first 100 chars of JD
        uploaded_files = request.files.getlist('resumes')

        if not jd.strip():
            app.logger.error("Empty job description")
            return jsonify({"error": "Job description cannot be empty"}), 400
            
        if not uploaded_files:
            app.logger.error("No resume files in request")
            return jsonify({"error": "No resume files provided"}), 400

        # Process each resume
        results = []
        for uploaded_file in uploaded_files:
            try:
                app.logger.info(f"Processing resume: {uploaded_file.filename}")
                
                # Extract text from PDF
                text = extract_pdf_text(uploaded_file)
                if not text:
                    app.logger.warning(f"Skipping {uploaded_file.filename} - no text extracted")
                    continue

                # Get AI evaluation
                prompt_filled = input_prompt.format(text=text, jd=jd)
                response = get_openai_response(prompt_filled)
                if not response:
                    app.logger.warning(f"Skipping {uploaded_file.filename} - no AI response")
                    continue

                # Parse AI response
                try:
                    parsed_response = json.loads(response)
                    app.logger.info(f"AI Response for {uploaded_file.filename}: {parsed_response}")
                    
                    # Clean up match percentage
                    match_percentage_str = parsed_response.get("JD Match", "0").replace("%", "").strip()
                    try:
                        match_percentage = float(match_percentage_str)
                    except ValueError:
                        app.logger.warning(f"Invalid match percentage for {uploaded_file.filename}")
                        match_percentage = 0.0

                    # Compile result
                    result = {
                        "filename": uploaded_file.filename,
                        "match_percentage": match_percentage,
                        "missing_keywords": parsed_response.get("MissingKeywords", []),
                        "profile_summary": parsed_response.get("Profile Summary", "")
                    }
                    results.append(result)
                    app.logger.info(f"Processed result for {uploaded_file.filename}: {result}")

                except json.JSONDecodeError as e:
                    app.logger.error(f"JSON parsing error for {uploaded_file.filename}: {str(e)}")
                    continue

            except Exception as e:
                app.logger.error(f"Error processing {uploaded_file.filename}: {str(e)}")
                continue

        if not results:
            app.logger.warning("No results generated for any resume")
            return jsonify({
                "status": "warning",
                "message": "No results could be generated",
                "ranked_candidates": []
            })

        # Sort results by match percentage
        sorted_results = sorted(results, key=lambda x: x["match_percentage"], reverse=True)

        app.logger.info(f"Successfully processed {len(results)} resumes")
        app.logger.info(f"Final response data: {sorted_results}")
        
        # Format response for Laravel
        formatted_results = []
        for result in sorted_results:
            formatted_results.append({
                "filename": result["filename"],
                "analysis": {
                    "match_percentage": result["match_percentage"],
                    "missing_keywords": result["missing_keywords"],
                    "profile_summary": result["profile_summary"]
                }
            })

        return jsonify(formatted_results)

    except Exception as e:
        app.logger.error(f"Server error: {str(e)}")
        return jsonify([{
            "filename": "error.pdf",
            "error": str(e)
        }]), 500

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({"status": "healthy"}), 200

if __name__ == '__main__':
    port = int(os.environ.get('PORT', 5000))
    app.run(host='0.0.0.0', port=port) 