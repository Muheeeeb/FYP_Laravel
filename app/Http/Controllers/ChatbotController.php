<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string'
            ]);

            $apiKey = env('OPENAI_API_KEY');
            
            // Initialize cURL
            $ch = curl_init('https://api.openai.com/v1/chat/completions');
            
            // Prepare the data
            $data = [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $validated['message']
                    ]
                ]
            ];

            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey
            ]);
            
            // Execute the request
            $response = curl_exec($ch);
            
            // Check for cURL errors
            if (curl_errno($ch)) {
                throw new \Exception('cURL error: ' . curl_error($ch));
            }
            
            // Get HTTP status code
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode !== 200) {
                throw new \Exception('HTTP error: ' . $httpCode . ' Response: ' . $response);
            }
            
            // Close cURL
            curl_close($ch);
            
            // Decode response
            $result = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('JSON decode error: ' . json_last_error_msg());
            }
            
            if (!isset($result['choices'][0]['message']['content'])) {
                throw new \Exception('Invalid response format from OpenAI');
            }

            return response()->json([
                'message' => $result['choices'][0]['message']['content']
            ]);

        } catch (\Exception $e) {
            \Log::error('ChatBot Error: ' . $e->getMessage());
            
            if (config('app.debug')) {
                return response()->json([
                    'error' => true,
                    'debug_message' => $e->getMessage()
                ], 500);
            }
            
            return response()->json([
                'error' => true,
                'message' => 'An error occurred. Please try again.'
            ], 500);
        }
    }
}