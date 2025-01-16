<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dean Dashboard - SZABIST Hiring Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

     <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <style>
    /* Previous CSS styles remain the same */
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

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }

    .invalid-feedback {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.25rem;
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
                DEAN 
            </span>
        </div>
        <ul class="nav-links">
            <li><a href="{{ route('dean.dashboard') }}" class="active"><i class="fas fa-home"></i>Dashboard</a></li>
            <li><a href="{{ route('dean.analytics') }}" class="active"><i class="fas fa-chart-bar"></i>Analytics</a>
            </li>
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

        <div class="container mt-5">
            <h1>Dean Dashboard</h1>

            <!-- Notifications -->
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <!-- Job Requests Pending Approval -->
            <div class="card">
                <div class="card-header">Pending Job Requests</div>
                <div class="card-body">
                    @if($jobRequests->isEmpty())
                    <p>No pending jobs.</p>
                    @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jobRequests as $request)
                            <tr>
                                <td>{{ $request->department->name }}</td>
                                <td>{{ $request->position }}</td>
                                <td>{{ $request->description }}</td>
                                <td>
                                    <form action="{{ route('dean.approveRequest', $request->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#rejectModal-{{ $request->id }}">Reject</button>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal-{{ $request->id }}" tabindex="-1"
                                        aria-labelledby="rejectModalLabel-{{ $request->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rejectModalLabel-{{ $request->id }}">
                                                        Reject Job Request</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('dean.rejectRequest', $request->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="rejection_comment">Comment</label>
                                                            <textarea name="rejection_comment" id="rejection_comment"
                                                                class="form-control" required></textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-danger">Reject</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Reject Modal -->
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>

<script>
              window.onload = function () {
                if (performance.navigation.type === 2) {
                    // This is a back button navigation
                    fetch('/logout', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                    }).then(() => {
                        window.location.href = '/login';
                    });
                }
            };

          
    </script>
        
</body>

</html>