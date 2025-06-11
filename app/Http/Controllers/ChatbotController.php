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
            // Return a test response to check if basic communication works
            return response()->json([
                'message' => 'This is a test response. If you see this, the connection is working.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}