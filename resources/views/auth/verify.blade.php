<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification - HireSmart</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
        }

        .header {
            background: white;
            padding: 15px 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header img {
            height: 40px;
        }

        .header a {
            color: #666;
            text-decoration: none;
            font-weight: 500;
        }

        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 70px);
        }

        .verify-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            color: #0066ff;
            font-size: 24px;
            margin: 0 0 10px 0;
            font-weight: 500;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-label {
            display: block;
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            color: #666;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #0066ff;
            box-shadow: 0 0 0 2px rgba(0, 102, 255, 0.1);
        }

        .verify-button {
            width: 100%;
            padding: 12px;
            background: #0066ff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s ease;
            margin: 20px 0;
        }

        .verify-button:hover {
            background: #0052cc;
        }

        .back-link {
            color: #0066ff;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="SZABIST Logo">
            <a href="{{ url('/') }}">Home</a>
        </div>
    </header>

    <div class="main-content">
        <div class="verify-container">
            <h1>OTP Verification</h1>
            <p class="subtitle">Enter the OTP sent to your email to verify your identity</p>

            <form method="POST" action="{{ route('verify.code') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">OTP Code</label>
                    <input type="text" 
                           class="form-control" 
                           name="code" 
                           maxlength="6" 
                           required 
                           autofocus>
                </div>

                <button type="submit" class="verify-button">Verify OTP</button>
            </form>

            <a href="{{ route('login') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
               class="back-link">Back to Login</a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <script>
        document.querySelector('.form-control').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>