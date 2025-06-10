<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Requests - HOD Dashboard</title>
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
            <h1>Approved Job Requests</h1>
            <div class="user-info">
                <a href="{{ route('hod.analytics') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Analytics
                </a>
            </div>
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
                        <td>{{ $request->status }}</td>
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
                        <td colspan="7" class="text-center py-4">No approved requests found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $requests->links() }}
        </div>
    </main>
</body>
</html>