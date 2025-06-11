<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string'
            ]);

            $client = new Client();
            
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
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
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            return response()->json([
                'message' => $result['choices'][0]['message']['content']
            ]);

        } catch (\Exception $e) {
            \Log::error('ChatBot Error: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'An error occurred. Please try again.'
            ], 500);
        }
    }
}