<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Digital Campaign</title>
</head>

<style>
  body, html {
      margin: 0;
      padding: 40px;
      font-family: Arial, sans-serif;
      background-color: #1e1e1e;
      color: #ffffff;
      height: 100vh;
  }
  .container {
      display: flex;
      height: 100vh;
      justify-content: center;
  }
  .register-container {
      flex: 0 1 600px;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #2a2a2a;
      padding: 40px;
  }
  .register-form {
      width: 100%;
      max-width: 500px;
  }
  h1 {
    font-size: 32px;
    margin-bottom: 10px;
    background: linear-gradient(to right, #ffffff, #000000);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  p {
    font-size: 16px;
    color: #a0a0a0;
    margin-bottom: 30px;
  }
  .card {
    background: linear-gradient(145deg, #2a2a2a, #3a3a3a);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    margin-bottom: 20px;
  }
  .input-group {
    margin-bottom: 20px;
  }
  .input-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
  }
  .input-group input {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 10px;
    background-color: #3a3a3a;
    color: white;
    font-size: 16px;
  }
  .error {
    color: #ff6b6b;
    font-size: 14px;
    margin-top: 5px;
  }
  .register-button {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 10px;
    background: linear-gradient(to right, #00226e, #8f8e93);
    color: white;
    font-size: 18px;
    cursor: pointer;
    transition: transform 0.2s;
  }
  .register-button:hover {
    transform: translateY(-2px);
  }
  .login-link {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
  }
  .login-link a {
    color: #ededed;
    text-decoration: none;
  }
</style>

<body>
  <div class="container">
  <header
      style="position: fixed; width: 100%; top: 0; left: 0; z-index: 1000; padding: 15px 0; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); transition: all 0.3s ease;">
      <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <nav style="display: flex; justify-content: space-between; align-items: center;">
          <!-- Logo -->
          <div style="flex-shrink: 0;">
            <a href="{{ url('/') }}">
              <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" style="height: 40px; display: block;">
            </a>
          </div>
        </nav>
      </div>
    </header>
    <div class="register-container">
      <div class="register-form">
        <h1>Create Your Account</h1>
      
        
        <form action="{{ route('register') }}" method="POST">
          @csrf
          <div class="card">
            <div class="input-group">
              <label for="name">Name:</label>
              <input type="text" id="name" name="name" value="{{ old('name') }}" required>
              @error('name')
                <div class="error">{{ $message }}</div>
              @enderror
            </div>

            <div class="input-group">
              <label for="email">Email:</label>
              <input type="email" id="email" name="email" value="{{ old('email') }}" required>
              @error('email')
                <div class="error">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="input-group">
              <label for="password">Password:</label>
              <input type="password" id="password" name="password" required>
              @error('password')
                <div class="error">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="input-group">
              <label for="password_confirmation">Confirm Password:</label>
              <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
          </div>

          <button type="submit" class="register-button">Register</button>
        </form>
        
        <div class="login-link">
          Already have an account? <a href="{{ route('login') }}">Login</a>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/logout.js') }}"></script>
</body>
</html>
