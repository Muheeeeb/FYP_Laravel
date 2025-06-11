<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $message = $request->input('message', '');
            $message = strtolower(trim($message));

            $response = $this->getResponse($message);

            return response()->json(['message' => $response]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Hello! How can I help you with your job application today?'
            ]);
        }
    }

    private function getResponse($message)
    {
        if (str_contains($message, 'hi') || str_contains($message, 'hello')) {
            return 'Hello! How can I help you with your job application today?';
        }

        if (str_contains($message, 'job') || str_contains($message, 'position') || str_contains($message, 'vacancy')) {
            return 'We have several positions available at SZABIST. You can view all current openings on our Jobs page. Are you looking for any specific role?';
        }

        if (str_contains($message, 'faculty') || str_contains($message, 'teacher') || str_contains($message, 'professor')) {
            return 'For faculty positions, you\'ll need:\n- PhD/MS in relevant field\n- Teaching experience\n- Research publications\nWould you like to know more?';
        }

        if (str_contains($message, 'apply') || str_contains($message, 'how to')) {
            return 'To apply for a position:\n1. Browse our current openings\n2. Click "Apply Now" on the desired position\n3. Fill out the application form\n4. Upload your CV/Resume\n5. Submit your application\n\nNeed help with any of these steps?';
        }

        if (str_contains($message, 'requirement') || str_contains($message, 'qualify') || str_contains($message, 'eligib')) {
            return 'General requirements include:\n- Relevant degree\n- Required experience\n- Strong communication skills\n\nSpecific requirements vary by position. Which role are you interested in?';
        }

        if (str_contains($message, 'contact') || str_contains($message, 'reach') || str_contains($message, 'email')) {
            return 'You can contact us at:\nEmail: info@szabist-isb.edu.pk\nPhone: +92-51-4863363-65\nAddress: Street 9, Plot 67, Sector H-8/4, Islamabad';
        }

        if (str_contains($message, 'thank')) {
            return 'You\'re welcome! Let me know if you need anything else.';
        }

        return 'I\'m here to help with your job application process. You can ask about:\n- Available positions\n- Application requirements\n- How to apply\n- Contact information';
    }
}