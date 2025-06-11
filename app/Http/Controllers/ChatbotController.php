<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $message = $request->input('message', '');
            
            // System message to give context about SZABIST
            $systemMessage = "You are a helpful assistant for SZABIST (Shaheed Zulfikar Ali Bhutto Institute of Science and Technology) job portal. 
            Your main role is to help users with job applications, provide information about available positions, 
            and answer questions about faculty requirements, application processes, and general inquiries about SZABIST careers.
            Keep responses professional, concise, and focused on SZABIST employment matters.
            Always maintain a helpful and professional tone.";

            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $systemMessage],
                    ['role' => 'user', 'content' => $message]
                ],
                'max_tokens' => 300,
                'temperature' => 0.7,
            ]);

            $response = $result->choices[0]->message->content;

            // Log successful response
            Log::info('ChatGPT Response', [
                'user_message' => $message,
                'response' => $response
            ]);

            return response()->json(['message' => $response]);

        } catch (\Exception $e) {
            // Log the error
            Log::error('ChatGPT Error', [
                'message' => $e->getMessage(),
                'user_message' => $message ?? 'No message',
                'trace' => $e->getTraceAsString()
            ]);

            // Return a friendly error message
            return response()->json([
                'message' => "I apologize, but I'm having trouble connecting right now. Please try asking your question again, or you can contact SZABIST directly at careers@szabist-isb.edu.pk"
            ], 200); // Return 200 to handle error gracefully on frontend
        }
    }
}