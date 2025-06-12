from flask import Flask, request, jsonify
import PyPDF2 as pdf
import json
import os
import logging
import time
from logging.handlers import RotatingFileHandler
import openai
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
        app.logger.warning("python-dotenv not installed, skipping .env file load")

if not API_KEY:
    raise ValueError("OpenAI API key not found in environment variables")

# Set the API key for OpenAI
openai.api_key = API_KEY

@app.route('/health', methods=['GET'])
def health_check():
    return jsonify({"status": "healthy", "timestamp": time.time()})

@app.route('/rank-cv', methods=['POST'])
def rank_cv():
    try:
        # Get job description and CV text from request
        data = request.get_json()
        if not data or 'job_description' not in data or 'cv_text' not in data:
            return jsonify({"error": "Missing required fields"}), 400

        job_description = data['job_description']
        cv_text = data['cv_text']

        # Create prompt for GPT
        prompt = f"""
        Job Description:
        {job_description}

        CV Text:
        {cv_text}

        Please analyze this CV against the job description and provide:
        1. A match percentage (0-100)
        2. Key matching skills
        3. Missing skills
        4. Brief explanation of the rating
        Format as JSON with keys: match_percentage, matching_skills, missing_skills, explanation
        """

        # Call OpenAI API
        response = openai.ChatCompletion.create(
            model="gpt-3.5-turbo",
            messages=[
                {"role": "system", "content": "You are a professional CV analyzer. Provide accurate and constructive analysis."},
                {"role": "user", "content": prompt}
            ],
            temperature=0.7
        )

        # Extract the response
        analysis = response.choices[0].message.content

        # Parse the JSON response
        try:
            analysis_json = json.loads(analysis)
            return jsonify(analysis_json)
        except json.JSONDecodeError:
            app.logger.error(f"Failed to parse OpenAI response as JSON: {analysis}")
            return jsonify({"error": "Failed to parse analysis results"}), 500

    except Exception as e:
        app.logger.error(f"Error in rank-cv endpoint: {str(e)}")
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    port = int(os.getenv('PORT', 5000))
    app.run(host='0.0.0.0', port=port)