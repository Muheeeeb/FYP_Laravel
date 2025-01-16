<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - HireSmart & AI Based Hiring Platform</title>
    <link rel="icon" type="image/png" sizes="56x56" href="{{ asset('images/fav-icon/icon.png') }}">
</head>

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

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23495057' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 36px;
}

.remember-forgot {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
}

.remember-me {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #495057;
    font-size: 14px;
}

.forgot-password {
    color: #006dd7;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
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

/* Footer Styles */
.footer {
    background: white;
    border-top: 1px solid #dee2e6;
    padding: 40px 0 20px;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
}

.footer-section h4 {
    color: #343a40;
    margin-bottom: 20px;
    font-size: 16px;
}

.footer-section p {
    color: #6c757d;
    line-height: 1.6;
    font-size: 14px;
}

.footer-bottom {
    text-align: center;
    padding-top: 20px;
    margin-top: 20px;
    border-top: 1px solid #dee2e6;
    color: #6c757d;
    font-size: 14px;
}
</style>

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

        <!-- Login Section -->
        <section class="login-section">
            <div class="login-container">
                <div class="login-header">
                    <h1>Welcome Back</h1>
                    <p>Login to Your HireSmart Account</p>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        @error('password')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="remember-forgot">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span>Remember Me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                    </div>

                    <button type="submit" class="login-button">Login</button>
                </form>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-section">
                    <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="Logo" style="height: 40px; margin-bottom: 20px;">
                    <p>Street 9, Plot 67, Sector H-8/4<br>Islamabad, Pakistan</p>
                    <p>careers@szabist-isb.edu.pk<br>+92-51-4863363-65</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <p><a href="#" style="color: #6c757d; text-decoration: none;">How it Works</a></p>
                    <p><a href="#" style="color: #6c757d; text-decoration: none;">About Us</a></p>
                    <p><a href="#" style="color: #6c757d; text-decoration: none;">Contact</a></p>
                </div>
                <div class="footer-section">
                    <h4>Subscribe to Newsletter</h4>
                    <input type="email" class="form-control" placeholder="Enter your email">
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 HireSmart. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>