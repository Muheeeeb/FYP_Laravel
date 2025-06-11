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
            if (!env('OPENAI_API_KEY')) {
                throw new Exception('OpenAI API key not found');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a SZABIST recruitment assistant. Be concise.'],
                    ['role' => 'user', 'content' => $request->input('message')]
                ],
                'max_tokens' => 100
            ]);

            if ($response->successful()) {
                return response()->json([
                    'message' => $response->json('choices.0.message.content')
                ])->header('Access-Control-Allow-Origin', '*')
                  ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
                  ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
            }

            throw new Exception($response->body());

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Service temporarily unavailable. Please try again.',
                'error' => $e->getMessage()
            ], 500)->header('Access-Control-Allow-Origin', '*')
                  ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
                  ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        }
    }
}