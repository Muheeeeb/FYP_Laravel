<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $request->session()->regenerate();
            
            // Add 2FA code generation and email
            try {
                // Generate and save verification code
                $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                $user->verification_code = $code;
                $user->code_expires_at = now()->addMinutes(15);
                $user->save();

                // Send verification email
                Mail::raw(
                    "Your SZABIST Portal verification code is: $code\n\nThis code will expire in 15 minutes.", 
                    function($message) use ($user) {
                        $message->to($user->email)
                               ->subject('Login Verification Code')
                               ->from('nimra3261@gmail.com');
                });

                return redirect()->route('verify.form');
                
            } catch (\Exception $e) {
                \Log::error('2FA Error: ' . $e->getMessage());
                return back()->with('error', 'An error occurred during login.');
            }
        }

        return back()
            ->withInput()
            ->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'phone' => $validatedData['phone'] ?? null,
            'role' => 'user',
        ]);

        Auth::login($user);
        return redirect()->route('home')
            ->with('success', 'Registration successful!');
    }

    private function routeToDashboard($role)
    {
        switch ($role) {
            case 'hod':
                return redirect()->route('hod.dashboard');
            case 'dean':
                return redirect()->route('dean.dashboard');
            case 'hr':
                return redirect()->route('hr.dashboard');
            default:
                return redirect()->route('home');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }

    public function showVerifyForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        try {
            $user = Auth::user();
            $code = $request->code;

            if (!$user || !$code) {
                return redirect()->route('login');
            }

            if ($user->verification_code !== $code) {
                return back()->with('error', 'Invalid verification code.');
            }

            if (now()->isAfter($user->code_expires_at)) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Verification code has expired. Please login again.');
            }

            // Clear the code
            $user->verification_code = null;
            $user->code_expires_at = null;
            $user->save();

            // Set session
            session(['verified' => true]);

            // Use your existing routing method
            return $this->routeToDashboard($user->role);

        } catch (\Exception $e) {
            \Log::error('2FA Verification Error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred during verification.');
        }
    }
}

