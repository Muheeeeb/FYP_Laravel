<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Requests - HOD Dashboard</title>
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
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        /* Table Styles */
        .requests-table {
            width: 100%;
            background: var(--white);
            border-radius: 0.5rem;
            box-shadow: var(--card-shadow);
            margin-top: 1rem;
            overflow: hidden;
        }

        .requests-table th,
        .requests-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .requests-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #475569;
        }

        .requests-table tr:hover {
            background-color: #f8fafc;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
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

        .status-posted {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .pagination {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .pagination a {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            background-color: var(--white);
            color: var(--primary-color);
            text-decoration: none;
            box-shadow: var(--card-shadow);
        }

        .pagination a:hover {
            background-color: #f8fafc;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            background-color: var(--primary-color);
            color: var(--white);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-sm:hover {
            background-color: var(--secondary-color);
        }

        .requests-table td {
            vertical-align: middle;
        }

        .requests-table td:last-child {
            text-align: center;
            width: 120px;
        }

        .view-details-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            white-space: nowrap;
        }

        /* Back button style */
        .back-btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            background-color: var(--primary-color);
            color: var(--white);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background-color: var(--secondary-color);
        }

        .search-container {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: var(--card-shadow);
        }

        .search-form input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .btn-secondary {
            background-color: #6b7280;
            color: var(--white);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        .pagination-container {
            margin-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: var(--white);
            border-radius: 0.5rem;
            box-shadow: var(--card-shadow);
        }

        .pagination-info {
            color: var(--text-color);
        }

        .pagination-links {
            display: flex;
            align-items: center;
        }

        .pagination {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 0.5rem;
        }

        .page-item {
            margin: 0;
        }

        .page-link {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            border: 1px solid #e5e7eb;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }

        .page-link:hover:not(.disabled) {
            background-color: var(--background-color);
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }

        /* Bootstrap 4 pagination styles */
        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.25rem;
            margin: 0;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: var(--primary-color);
            background-color: #fff;
            border: 1px solid #dee2e6;
        }

        .page-item:first-child .page-link {
            margin-left: 0;
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }

        .page-item:last-child .page-link {
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            cursor: auto;
            background-color: #fff;
            border-color: #dee2e6;
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
            <h1>All Job Requests</h1>
            <div class="user-info">
                <a href="{{ route('hod.analytics') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Analytics
                </a>
            </div>
        </div>

        <!-- Search Form -->
        <div class="search-container">
            <form action="{{ route('hod.requests.all') }}" method="GET" class="search-form">
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="flex: 1;">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Search by position or department..."
                            value="{{ request('search') }}"
                            style="width: 100%; padding: 0.5rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; outline: none;"
                        >
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" class="btn btn-primary" style="white-space: nowrap;">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('hod.requests.all') }}" class="btn btn-secondary" style="background-color: #6b7280; color: white; text-decoration: none;">
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <table class="requests-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                    <tr>
                        <td>#{{ $request->id }}</td>
                        <td>{{ $request->position }}</td>
                        <td>{{ $request->department->name }}</td>
                        <td>
                            <span class="status-badge status-{{ $request->status }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                        <td>{{ $request->updated_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('hod.requests.show', $request->id) }}" class="btn btn-sm view-details-btn">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No requests found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Updated pagination section to match admin style -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                Showing {{ $requests->firstItem() ?? 0 }} to {{ $requests->lastItem() ?? 0 }} of {{ $requests->total() }} results
            </div>
            <div>
                {{ $requests->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </main>
</body>
</html>
