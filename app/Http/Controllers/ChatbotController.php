<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        try {
            $message = $request->input('message');
            
            if (empty($message)) {
                return response()->json([
                    'message' => 'Please provide a message'
                ], 400);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful recruitment assistant for SZABIST University. You help candidates with their job applications and provide information about the recruitment process. Keep your responses concise and professional.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ],
                'max_tokens' => 150,
                'temperature' => 0.7,
            ]);

            if ($response->failed()) {
                throw new Exception('Failed to get response from OpenAI API: ' . $response->body());
            }

            $responseData = $response->json();
            
            if (!isset($responseData['choices'][0]['message']['content'])) {
                throw new Exception('Invalid response format from OpenAI API');
            }

            return response()->json([
                'message' => trim($responseData['choices'][0]['message']['content'])
            ]);

        } catch (Exception $e) {
            \Log::error('Chatbot Error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'I apologize, but I am having trouble processing your request right now. Please try again later.'
            ], 500);
        }
    }
}