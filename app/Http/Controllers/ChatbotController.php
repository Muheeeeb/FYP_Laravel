<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Orhanerday\OpenAi\OpenAi;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string'
            ]);

            $open_ai = new OpenAi(env('OPENAI_API_KEY'));

            $result = $open_ai->chat([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $validated['message']
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 150
            ]);

            $response = json_decode($result, true);

            if (isset($response['error'])) {
                throw new \Exception($response['error']['message'] ?? 'OpenAI API error');
            }

            return response()->json([
                'message' => $response['choices'][0]['message']['content']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}