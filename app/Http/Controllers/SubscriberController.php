<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function subscribe(Request $request)
    {
        // Validate the email
        $request->validate([
            'email' => 'required|email|unique:subscribers,email'
        ]);

        try {
            // Create new subscriber
            Subscriber::create([
                'email' => $request->email,
                'is_active' => true
            ]);

            $message = 'Thank you for subscribing! You will receive notifications for new job openings.';

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Subscription error: ' . $e->getMessage());
            
            $message = 'Something went wrong. Please try again.';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }

            return back()->with('error', $message);
        }
    }

    public function unsubscribe($email)
    {
        try {
            $subscriber = Subscriber::where('email', $email)->first();
            
            if ($subscriber) {
                $subscriber->update(['is_active' => false]);
                return redirect()->route('home')->with('success', 'You have been successfully unsubscribed from job notifications.');
            }

            return redirect()->route('home')->with('error', 'Email not found in our subscribers list.');
        } catch (\Exception $e) {
            \Log::error('Unsubscribe error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Something went wrong. Please try again.');
        }
    }
}