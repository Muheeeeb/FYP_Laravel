<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string'
            ]);

            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $validated['message']]
                ]
            ]);

            return response()->json([
                'message' => $response->choices[0]->message->content
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