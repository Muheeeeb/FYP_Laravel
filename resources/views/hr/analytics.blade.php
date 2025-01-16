<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dean Analytics - SZABIST Hiring Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
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
    }

    .sidebar-logo {
        display: flex;
        align-items: center;
        color: var(--white);
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-logo img {
        width: 40px;
        margin-right: 0.5rem;
    }

    .nav-links {
        list-style: none;
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

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 0.5rem;
    }

    .alert-success {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }

    .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
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

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    th {
        font-weight: 600;
        background-color: #f8fafc;
    }

    .container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    }

    /* Table Styles */
    .status-section {
        margin-bottom: 30px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .section-header {
        padding: 15px 20px;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .approved-header {
        background: #dcfce7;
        color: #166534;
    }

    .pending-header {
        background: #dbeafe;
        color: #1e40af;
    }

    .rejected-header {
        background: #fee2e2;
        color: #991b1b;
    }

    .request-table {
        width: 100%;
        border-collapse: collapse;
    }

    .request-table th {
        background: #f8fafc;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
    }

    .request-table td {
        padding: 12px;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
    }

    .request-table tr:hover {
        background: #f8fafc;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 14px;
        font-weight: 500;
        display: inline-block;
    }

    .approved-badge {
        background: #dcfce7;
        color: #166534;
    }

    .pending-badge {
        background: #dbeafe;
        color: #1e40af;
    }

    .rejected-badge {
        background: #fee2e2;
        color: #991b1b;
    }

    .timestamp {
        color: #64748b;
        font-size: 14px;
    }

    .position-cell {
        font-weight: 500;
    }

    .department-cell {
        color: #64748b;
    }

    /* Chart Styles */
    .stats-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 30px;
    }

    .stats-header {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #1e293b;
    }

    .chart-container {
        display: flex;
        height: 300px;
        padding: 20px;
        justify-content: space-around;
        align-items: flex-end;
        gap: 60px;
    }

    .bar-group {
        width: 80px;
        height: 250px;
        position: relative;
        display: flex;
        flex-direction: column-reverse;
    }

    .bar {
        width: 100%;
        transition: height 0.3s ease;
        position: relative;
    }

    .bar-approved {
        background: #86efac;
        border-radius: 6px 6px 0 0;
        /* Round top corners for top bar */
    }

    .legend {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-top: 30px;
        padding: 15px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        /* Makes it circular */
        display: inline-block;
        border: 2px solid white;
        box-shadow: 0 0 0 1px #e5e7eb;
    }

    .bar-pending {
        background: #93c5fd;
    }

    .bar-rejected {
        background: #fca5a5;
        border-radius: 0 0 6px 6px;
        /* Round bottom corners for bottom bar */
    }

    .department-label {
        text-align: center;
        font-weight: 500;
        color: #475569;
        margin-top: 10px;
        position: absolute;
        bottom: -56px;
        width: 100%;
    }

    .bar-label {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        color: #1e293b;
        font-size: 15px;
        font-weight: 700;
    }

    /* Add tooltips for better data visibility */
    .bar {
    width: 100%;
    transition: height 0.3s ease;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
}

    .bar:hover::after {
        content: attr(data-count);
        position: absolute;
        right: -35px;
        top: 50%;
        transform: translateY(-50%);
        background: #1e293b;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .chart-container {
            height: 200px;
            gap: 30px;
        }

        .bar-group {
            width: 60px;
        }
    }
    </style>
</head>

<body>
<aside class="sidebar">
        <div class="sidebar-logo" style="
            text-align: center;
            padding: 15px 0;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
            <span style="
                font-size: 24px; 
                font-weight: 500; 
                color: #e2e8f0;
                font-family: 'Poppins', sans-serif;
                display: inline-block;">
                HR 
            </span>
        </div>
        <ul class="nav-links">
            <li><a href="{{ route('hr.dashboard') }}" class="active"><i class="fas fa-home"></i>Dashboard</a></li>
            <li><a href="{{ route('hr.job-posting') }}" class="active"><i class="fas fa-list"></i>Job Postings</a></li>
            <li><a href="{{ route('hr.analytics') }}" class="active"><i class="fas fa-chart-bar"></i>Analytics</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Welcome, {{ auth()->user()->name }}</h1>
            <div class="user-info">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>

        <div class="container">

            <div class="stats-container">
                <div class="stats-header">
                    Job Requests by Department
                </div>
                <div class="chart-container">
                    @foreach($departmentStats as $dept_id => $stats)
                    <div class="bar-group">
                        @php
                        $approved = $stats->where('status', 'Approved by Dean')->first()->count ?? 0;
                        $pending = $stats->where('status', 'Posted by HR')->first()->count ?? 0;
                        $rejected = $stats->where('status', 'Rejected by Dean')->first()->count ?? 0;
                        $total = $approved + $pending + $rejected;
                        $maxHeight = 250; // Maximum height in pixels

                        // Calculate percentages for height
                        $approvedHeight = $total > 0 ? ($approved / $total) * $maxHeight : 0;
                        $pendingHeight = $total > 0 ? ($pending / $total) * $maxHeight : 0;
                        $rejectedHeight = $total > 0 ? ($rejected / $total) * $maxHeight : 0;
                        @endphp

                        <div class="bar bar-approved" style="height: {{ $approvedHeight }}px"
                            data-count="Approved: {{ $approved }}">
                            @if($approved > 0)
                            <span class="bar-label">{{ $approved }}</span>
                            @endif
                        </div>
                        <div class="bar bar-pending" style="height: {{ $pendingHeight }}px"
                            data-count="Pending: {{ $pending }}">
                            @if($pending > 0)
                            <span class="bar-label">{{ $pending }}</span>
                            @endif
                        </div>
                        <div class="bar bar-rejected" style="height: {{ $rejectedHeight }}px"
                            data-count="Rejected: {{ $rejected }}">
                            @if($rejected > 0)
                            <span class="bar-label">{{ $rejected }}</span>
                            @endif
                        </div>
                        <div class="department-label">{{ $stats->first()->department_name }}</div>
                    </div>
                    @endforeach
                </div>

                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background: #86efac"></div>
                        <span>Approved Requests</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #93c5fd"></div>
                        <span>Posted by HR</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #fca5a5"></div>
                        <span>Rejected Requests</span>
                    </div>
                </div>
            </div>

            <!-- Approved Requests Table -->
            <div class="status-section">
                <div class="section-header approved-header">
                    ✓ Approved Requests
                </div>
                <table class="request-table">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Approved At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approvedRequests as $request)
                        <tr>
                            <td class="position-cell">{{ $request->position }}</td>
                            <td class="department-cell">Department {{ $request->department_id }}</td>
                            <td>{{ Str::limit($request->description, 50) }}</td>
                            <td><span class="status-badge approved-badge">Approved</span></td>
                            <td class="timestamp">
                                {{ Carbon\Carbon::parse($request->approved_by_dean_at)->format('M d, Y H:i:s') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Posted by HR Requests Table -->
            <div class="status-section">
                <div class="section-header pending-header">
                    ⏳ Posted by HR
                </div>
                <table class="request-table">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Posted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingRequests as $request)
                        <tr>
                            <td class="position-cell">{{ $request->position }}</td>
                            <td class="department-cell">Department {{ $request->department_id }}</td>
                            <td>{{ Str::limit($request->description, 50) }}</td>
                            <td><span class="status-badge pending-badge">Pending</span></td>
                            <td class="timestamp">
                                {{ Carbon\Carbon::parse($request->posted_by_hr_at)->format('M d, Y H:i:s') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Rejected Requests Table -->
            <div class="status-section">
                <div class="section-header rejected-header">
                    ✕ Rejected Requests
                </div>
                <table class="request-table">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Rejected At</th>
                            <th>Rejection Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rejectedRequests as $request)
                        <tr>
                            <td class="position-cell">{{ $request->position }}</td>
                            <td class="department-cell">Department {{ $request->department_id }}</td>
                            <td>{{ Str::limit($request->description, 50) }}</td>
                            <td><span class="status-badge rejected-badge">Rejected</span></td>
                            <td class="timestamp">
                                {{ Carbon\Carbon::parse($request->rejected_by_dean_at)->format('M d, Y H:i:s') }}
                            </td>
                            <td>{{ $request->rejection_comment }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>

</html>