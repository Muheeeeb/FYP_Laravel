<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Request Details - HOD Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            background-color: #1e2a3a;
            padding: 1.5rem;
            position: fixed;
            left: 0;
            top: 0;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            padding: 15px 0;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-links {
            list-style: none;
            padding: 0;
        }

        .nav-links li {
            margin-bottom: 0.5rem;
        }

        .nav-links a {
            color: #ffffff;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            opacity: 0.8;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background-color: rgba(255, 255, 255, 0.1);
            opacity: 1;
        }

        .nav-links i {
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

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
        }

        .btn-secondary {
            background-color: #64748b;
            color: var(--white);
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            background-color: #475569;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .details-card {
            background: var(--white);
            border-radius: 0.5rem;
            padding: 2rem;
            box-shadow: var(--card-shadow);
        }

        .detail-row {
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1rem;
        }

        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .detail-row label {
            display: block;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .detail-row span {
            display: block;
            color: var(--text-color);
            font-size: 1rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
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

        .status-posted {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .text-danger {
            color: #dc2626;
        }

        .header .user-info {
            display: flex;
            align-items: center;
        }

        .fa-arrow-left {
            margin-right: 4px;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-logo">
            <span style="font-size: 24px; font-weight: 500; color: #ffffff; margin-left: 10px;">
                HOD
            </span>
        </div>
        <ul class="nav-links">
            <li><a href="{{ route('hod.dashboard') }}"><i class="fas fa-home"></i>Dashboard</a></li>
            <li><a href="{{ route('hod.candidates') }}"><i class="fas fa-user-tie"></i>Candidates</a></li>
            <li><a href="{{ route('hod.analytics') }}"><i class="fas fa-chart-bar"></i>Analytics</a></li>
            <li><a href="{{ route('hod.settings') }}"><i class="fas fa-cog"></i>Settings</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Job Request Details</h1>
            <div class="user-info">
                <a href="{{ route('hod.requests.all') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to All Requests
                </a>
            </div>
        </div>

        <div class="details-card">
            <div class="detail-row">
                <label>Position</label>
                <span>{{ $request->position }}</span>
            </div>
            <div class="detail-row">
                <label>Department</label>
                <span>{{ $request->department->name }}</span>
            </div>
            <div class="detail-row">
                <label>Description</label>
                <span>{{ $request->description }}</span>
            </div>
            <div class="detail-row">
                <label>Justification</label>
                <span>{{ $request->justification }}</span>
            </div>
            <div class="detail-row">
                <label>Status</label>
                <span class="status-badge status-{{ $request->status }}">
                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                </span>
            </div>
            <div class="detail-row">
                <label>Created Date</label>
                <span>{{ $request->created_at->format('M d, Y') }}</span>
            </div>
            @if($request->rejection_comment)
            <div class="detail-row">
                <label>Rejection Comment</label>
                <span class="text-danger">{{ $request->rejection_comment }}</span>
            </div>
            @endif
        </div>
    </main>
</body>
</html>
