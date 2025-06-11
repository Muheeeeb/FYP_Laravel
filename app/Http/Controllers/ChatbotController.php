<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        try {
            $apiKey = env('OPENAI_API_KEY');
            
            if (empty($apiKey)) {
                return response()->json([
                    'message' => 'I apologize, but I am not configured properly. Please contact support.'
                ], 500);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a SZABIST recruitment assistant. Be concise.'],
                    ['role' => 'user', 'content' => $request->input('message')]
                ],
                'max_tokens' => 100,
                'temperature' => 0.7
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'I apologize, but I am having trouble connecting to my brain. Please try again.'
                ], 500);
            }

            $content = $response->json('choices.0.message.content');
            
            if (empty($content)) {
                return response()->json([
                    'message' => 'I apologize, but I could not generate a proper response. Please try again.'
                ], 500);
            }

            return response()->json(['message' => trim($content)]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'I apologize, but something went wrong. Please try again.'
            ], 500);
        }
    }
}