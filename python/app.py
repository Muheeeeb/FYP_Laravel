from flask import Flask, request, jsonify
import os
import requests
from flask_cors import CORS

# Initialize the Flask app
app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

API_KEY = os.getenv('OPENAI_API_KEY')
if not API_KEY:
    raise ValueError("OpenAI API key not found")

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

        # Make direct API call to OpenAI
        headers = {
            'Authorization': f'Bearer {API_KEY}',
            'Content-Type': 'application/json',
        }

        payload = {
            'model': 'gpt-3.5-turbo',
            'messages': [
                {'role': 'system', 'content': 'You are a professional CV analyzer.'},
                {'role': 'user', 'content': prompt}
            ]
        }

        response = requests.post(
            'https://api.openai.com/v1/chat/completions',
            headers=headers,
            json=payload,
            timeout=30
        )

        if response.status_code != 200:
            return jsonify({"error": f"OpenAI API error: {response.text}"}), response.status_code

        result = response.json()
        return jsonify({"response": result['choices'][0]['message']['content']})

    except requests.exceptions.RequestException as e:
        return jsonify({"error": f"Request error: {str(e)}"}), 500
    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    port = int(os.getenv('PORT', 5000))
    app.run(host='0.0.0.0', port=port)