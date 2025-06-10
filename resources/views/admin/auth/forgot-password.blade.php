<!DOCTYPE html>
<html>
<head>
    <title>Admin - Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .card-header {
            background-color: #343a40;
            color: white;
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
            padding: 1rem;
        }
        .card-header h4 {
            margin-bottom: 0;
            text-align: center;
        }
        .form-control {
            background-color: #f0f2f5;
            border: none;
            padding: 0.8rem;
        }
        .btn-primary {
            background-color: #343a40;
            border: none;
            padding: 0.8rem;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #23272b;
        }
        .btn-link {
            color: #343a40;
            text-decoration: none;
        }
        .btn-link:hover {
            color: #23272b;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Admin Password Reset</h4>
                    </div>
                    <div class="card-body p-4">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form action="{{ route('admin.password.email') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary mb-3">Send Reset Link</button>
                                <a href="{{ route('admin.login') }}" class="btn btn-link text-center">Back to Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
