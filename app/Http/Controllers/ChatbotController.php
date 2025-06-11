<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        try {
            $apiKey = env('OPENAI_API_KEY');
            Log::info('API Key status: ' . (empty($apiKey) ? 'missing' : 'present'));

            $response = Http::withToken($apiKey)
                ->timeout(60)
                ->retry(3, 100)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => $request->input('message', 'Hello')]
                    ]
                ]);

            Log::info('OpenAI Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json([
                'message' => $response->json('choices.0.message.content', 'Default response if something goes wrong')
            ]);

        } catch (\Exception $e) {
            Log::error('Detailed error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}