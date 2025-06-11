<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            // Log the incoming request
            \Log::info('ChatBot Request:', [
                'message' => $request->all(),
                'api_key_exists' => !empty(env('OPENAI_API_KEY'))
            ]);

            $validated = $request->validate([
                'message' => 'required|string'
            ]);

            $client = new Client();
            
            $apiUrl = 'https://api.openai.com/v1/chat/completions';
            $apiKey = env('OPENAI_API_KEY');
            
            \Log::info('ChatBot Making API call to: ' . $apiUrl);
            
            $requestData = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => $validated['message']]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 150
                ]
            ];
            
            \Log::info('ChatBot Request Data:', $requestData);
            
            $response = $client->post($apiUrl, $requestData);
            
            \Log::info('ChatBot Raw Response:', [
                'status' => $response->getStatusCode(),
                'body' => $response->getBody()->getContents()
            ]);
            
            // Need to rewind the stream after reading it
            $response->getBody()->rewind();
            
            $result = json_decode($response->getBody()->getContents(), true);
            
            if (!$result || !isset($result['choices'][0]['message']['content'])) {
                throw new \Exception('Invalid response format from OpenAI');
            }

            return response()->json([
                'message' => $result['choices'][0]['message']['content']
            ]);

        } catch (\Exception $e) {
            \Log::error('ChatBot Detailed Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // In development, return the actual error
            if (config('app.debug')) {
                return response()->json([
                    'error' => true,
                    'debug_message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ], 500);
            }
            
            return response()->json([
                'error' => true,
                'message' => 'An error occurred. Please try again.'
            ], 500);
        }
    }
}