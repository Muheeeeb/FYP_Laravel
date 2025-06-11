<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    private $responses = [
        'hello' => 'Hello! How can I help you with your job application today?',
        'hi' => 'Hi there! Looking to apply for a position at SZABIST?',
        'job' => 'We have various positions available. You can check our current openings on the Jobs page. What type of position are you interested in?',
        'faculty' => 'For faculty positions, you\'ll need: \n- PhD/MS in relevant field\n- Teaching experience\n- Research publications\nWould you like to know more?',
        'requirements' => 'General requirements include:\n- Relevant degree\n- Experience in the field\n- Strong communication skills\nWhich position are you interested in?',
        'apply' => 'To apply:\n1. Create an account\n2. Upload your CV\n3. Fill out the application form\n4. Submit required documents\nNeed help with any of these steps?',
        'contact' => 'You can reach us at:\nEmail: info@szabist-isb.edu.pk\nPhone: +92-51-4863363-65\nAddress: Street 9, Plot 67, Sector H-8/4, Islamabad',
        'thanks' => 'You\'re welcome! Let me know if you need anything else.',
        'default' => 'I\'m here to help with your job application process. You can ask about:\n- Available positions\n- Application requirements\n- How to apply\n- Contact information'
    ];

    public function sendMessage(Request $request)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string'
            ]);

            $userMessage = strtolower($validated['message']);
            
            $response = $this->responses['default'];
            
            foreach ($this->responses as $keyword => $reply) {
                if (str_contains($userMessage, $keyword)) {
                    $response = $reply;
                    break;
                }
            }

            return response()->json([
                'message' => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'I\'m here to help! Please ask about jobs, requirements, or how to apply.'
            ], 200); // Return 200 even for errors to avoid HTTP issues
        }
    }
}