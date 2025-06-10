<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - HireSmart & AI Based Hiring Platform</title>
    <link rel="icon" type="image/png" sizes="56x56" href="{{ asset('images/fav-icon/icon.png') }}">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background-color: #f0f2f5;
        }

        .main-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        .header {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Login Container Styles */
        .login-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 20px 40px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .login-container {
            background: white;
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #006dd7;
            font-size: 28px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .login-header p {
            color: #6c757d;
            font-size: 16px;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #495057;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 15px;
            color: #495057;
            background-color: #f8f9fa;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: #006dd7;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 109, 215, 0.1);
        }

        .login-button {
            width: 100%;
            padding: 12px;
            background: #006dd7;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .login-button:hover {
            background: #0056aa;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
            font-size: 14px;
        }

        .register-link a {
            color: #006dd7;
            text-decoration: none;
            font-weight: 500;
        }

        .error {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <!-- Header -->
        <header class="header">
            <div class="nav-container">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="Logo" style="height: 40px;">
                </a>
                <a href="{{ url('/') }}" style="color: #495057; text-decoration: none; font-weight: 500;">Home</a>
            </div>
        </header>

        <!-- Forgot Password Section -->
        <section class="login-section">
            <div class="login-container">
                <div class="login-header">
                    <h1>Forgot Password</h1>
                    <p>Enter your email to reset your password</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="login-button">Send Reset Link</button>

                    <div class="register-link">
                        <a href="{{ route('login') }}">Back to Login</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
</body>
</html>