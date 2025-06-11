<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $message = $request->input('message');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant for SZABIST job portal. Focus on helping users with job applications, providing information about positions, and answering questions about faculty requirements and application processes.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ],
                'max_tokens' => 300,
                'temperature' => 0.7,
            ]);

            if ($response->failed()) {
                Log::error('OpenAI API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'message' => $message
                ]);
                throw new \Exception('Failed to get response from OpenAI');
            }

            $responseData = $response->json();
            
            if (!isset($responseData['choices'][0]['message']['content'])) {
                Log::error('Unexpected OpenAI Response Format', [
                    'response' => $responseData
                ]);
                throw new \Exception('Unexpected response format from OpenAI');
            }

            return response()->json([
                'message' => $responseData['choices'][0]['message']['content']
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => "I apologize, but I'm having trouble connecting right now. Please try again in a moment."
            ], 200);
        }
    }
}