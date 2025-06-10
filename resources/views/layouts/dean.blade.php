<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dean Panel - @yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --background-color: #f1f5f9;
            --sidebar-color: #1e293b;
            --text-color: #334155;
            --white: #ffffff;
            --card-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            background-color: var(--sidebar-color);
            min-height: 100vh;
            position: fixed;
            width: 250px;
            padding: 1rem;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }

        .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--white);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 0.75rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 1.5rem;
            color: var(--text-color);
            margin: 0;
        }

        .card {
            background: var(--white);
            border-radius: 0.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }

        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        .table td {
            vertical-align: middle;
            padding: 0.75rem;
        }

        .table th {
            font-weight: 600;
            background-color: #f8fafc;
            padding: 0.75rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.5rem;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border-color: #86efac;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border-color: #fecaca;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .progress {
            height: 24px;
            margin-bottom: 0;
            min-width: 80px;
        }

        .progress-bar {
            line-height: 24px;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .modal-content {
            border-radius: 0.5rem;
            box-shadow: var(--card-shadow);
        }

        .modal-header {
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="text-white mb-4 text-center py-3">
                <h4>Dean Panel</h4>
                <small>{{ auth()->user()->department->name ?? 'Department' }}</small>
            </div>
            <nav>
                <a href="{{ route('dean.dashboard') }}" class="nav-link {{ request()->routeIs('dean.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('dean.analytics') }}" class="nav-link {{ request()->routeIs('dean.analytics') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Analytics
                </a>
                <a href="{{ route('dean.settings') }}" class="nav-link {{ request()->routeIs('dean.settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mt-auto">
                    @csrf
                    <button type="submit" class="nav-link border-0 w-100 text-start">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content w-100">
            @yield('content')
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set up CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Function to refresh CSRF token
        function refreshToken() {
            $.get('/csrf-token', function(data) {
                $('meta[name="csrf-token"]').attr('content', data.token);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': data.token
                    }
                });
            });
        }

        // Refresh token every 30 minutes
        setInterval(refreshToken, 30 * 60 * 1000);
    </script>
    @stack('scripts')
</body>
</html> 