<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HOD Panel - @yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --background-color: #f1f5f9;
            --sidebar-color: #1e293b;
            --text-color: #334155;
            --white: #ffffff;
            --success-color: #22c55e;
            --warning-color: #fbbf24;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
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
            z-index: 1000;
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

        /* Logout button styles */
        .logout-btn {
            color: #ef4444 !important;
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-top: 2rem;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444 !important;
        }

        .logout-btn i {
            width: 20px;
            text-align: center;
            margin-right: 0.75rem;
        }

        /* Analytics Page Styles */
        .card {
            background: var(--white);
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.5rem;
        }

        .bg-primary-light {
            background-color: rgba(37, 99, 235, 0.1);
        }

        .bg-success-light {
            background-color: rgba(34, 197, 94, 0.1);
        }

        .bg-warning-light {
            background-color: rgba(251, 191, 36, 0.1);
        }

        .bg-danger-light {
            background-color: rgba(239, 68, 68, 0.1);
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-color);
        }

        .stat-label {
            color: #64748b;
            font-size: 0.875rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 1.5rem;
        }

        .table {
            --bs-table-hover-bg: rgba(37, 99, 235, 0.05);
        }

        .badge {
            padding: 0.5em 0.75em;
            font-weight: 500;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 1.5rem;
        }

        /* Chart Container */
        canvas {
            max-width: 100%;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="text-white mb-4 text-center py-3">
                <h4>HOD Panel</h4>
                <small>{{ auth()->user()->department->name ?? 'Department' }}</small>
            </div>
            <nav class="d-flex flex-column h-100">
                <div>
                    <a href="{{ route('hod.dashboard') }}" class="nav-link {{ request()->routeIs('hod.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a href="{{ route('hod.candidates') }}" class="nav-link {{ request()->routeIs('hod.candidates') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Candidates
                    </a>
                    <a href="{{ route('hod.analytics') }}" class="nav-link {{ request()->routeIs('hod.analytics') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i> Analytics
                    </a>
                    <a href="{{ route('hod.settings') }}" class="nav-link {{ request()->routeIs('hod.settings') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </div>
                <div class="mt-auto">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
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
    @yield('scripts')
</body>
</html> 