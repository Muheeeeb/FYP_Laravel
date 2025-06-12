from flask import Flask, request, jsonify
import os
import openai
from flask_cors import CORS

# Initialize the Flask app
app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

# Set OpenAI API key
openai.api_key = os.getenv('OPENAI_API_KEY')

@app.route('/health', methods=['GET'])
def health_check():
    return jsonify({"status": "healthy"})

@app.route('/rank-cv', methods=['POST'])
def rank_cv():
    try:
        data = request.get_json()
        if not data or 'job_description' not in data or 'cv_text' not in data:
            return jsonify({"error": "Missing required fields"}), 400

        prompt = f"""
        Job Description:
        {data['job_description']}

        CV Text:
        {data['cv_text']}

        Please analyze this CV against the job description and provide:
        1. A match percentage (0-100)
        2. Key matching skills
        3. Missing skills
        4. Brief explanation of the rating
        Format as JSON with keys: match_percentage, matching_skills, missing_skills, explanation
        """

        response = openai.ChatCompletion.create(
            model="gpt-3.5-turbo",
            messages=[
                {"role": "system", "content": "You are a professional CV analyzer."},
                {"role": "user", "content": prompt}
            ]
        )

        return jsonify({"response": response.choices[0].message.content})

    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    port = int(os.getenv('PORT', 5000))
    app.run(host='0.0.0.0', port=port)