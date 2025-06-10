<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SZABIST Hiring Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: var(--sidebar-color);
            padding: 1.5rem;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            color: var(--white);
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-links {
            list-style: none;
            padding: 0;
            flex-grow: 1;
        }

        .nav-links li {
            margin-bottom: 0.5rem;
        }

        .nav-links a {
            color: #94a3b8;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--white);
        }

        .nav-links i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .logout-btn {
            margin-top: auto;
            background: none;
            border: none;
            color: #94a3b8;
            text-align: left;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--white);
        }

        .logout-btn i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
            width: calc(100% - 250px);
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

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
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

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-approved { background-color: #dcfce7; color: #166534; }
        .status-rejected { background-color: #fee2e2; color: #991b1b; }
        .status-closed { background-color: #f3f4f6; color: #1f2937; }
        .status-active { background-color: #dbeafe; color: #1e40af; }
        .status-shortlisted { background-color: #e0f2fe; color: #0369a1; }
        .status-interviewed { background-color: #f0fdf4; color: #166534; }
        .status-hired { background-color: #dcfce7; color: #166534; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-logo">
            <span>HR Panel</span>
        </div>
        <ul class="nav-links">
            <li><a href="{{ route('hr.dashboard') }}" class="{{ request()->routeIs('hr.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>Dashboard
            </a></li>
            <li><a href="{{ route('hr.manage.jobs') }}" class="{{ request()->routeIs('hr.manage.jobs') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i>Manage Jobs
            </a></li>
            <li><a href="{{ route('hr.job-posting') }}" class="{{ request()->routeIs('hr.job-posting') ? 'active' : '' }}">
                <i class="fas fa-list"></i>Job Postings
            </a></li>
            <li><a href="{{ route('hr.applications') }}" class="{{ request()->routeIs('hr.applications*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>Applications
            </a></li>
            <li><a href="{{ route('hr.analytics') }}" class="{{ request()->routeIs('hr.analytics') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>Analytics
            </a></li>
        </ul>
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>Logout
            </button>
        </form>
    </aside>

    <main class="main-content">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html> 