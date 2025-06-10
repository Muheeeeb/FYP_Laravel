<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status - SZABIST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            padding-top: 70px; /* Added for fixed navbar */
        }
        .status-card {
            max-width: 600px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 28px;
            color: #333;
        }
        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
            display: inline-block;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-reviewed {
            background-color: #cce5ff;
            color: #004085;
        }
        .status-accepted {
            background-color: #d4edda;
            color: #155724;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }
        .navbar-brand img {
            height: 40px;
        }
        .btn-track-another {
            background-color: #006dd7;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }
        .btn-track-another:hover {
            background-color: #0056b3;
            color: white;
            text-decoration: none;
        }
        .application-details .row {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .application-details .row:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="SZABIST">
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="status-card">
            <div class="header">
                <h1>Application Status</h1>
            </div>

            @if(isset($application) && $application)
                <div class="application-details">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Position Applied:</div>
                        <div class="col-md-8">{{ $application->jobPosting->title ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Applicant Name:</div>
                        <div class="col-md-8">{{ $application->name ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Application Date:</div>
                        <div class="col-md-8">
                            {{ $application->created_at ? $application->created_at->format('F d, Y') : 'N/A' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Current Status:</div>
                        <div class="col-md-8">
                            <span class="badge bg-{{ $application->status_class }}">
                                {{ $application->display_status }}
                            </span>
                        </div>
                    </div>

                    @if($application->status_updated_at)
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Last Updated:</div>
                        <div class="col-md-8">{{ $application->status_updated_at->format('F d, Y') }}</div>
                    </div>
                    @endif

                    @if($application->has_interview_details)
                    <div class="alert alert-info mt-4">
                        <h5 class="alert-heading">Interview Details</h5>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Date:</div>
                            <div class="col-md-8">{{ $application->formatted_interview_date }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Time:</div>
                            <div class="col-md-8">{{ $application->formatted_interview_time }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Location:</div>
                            <div class="col-md-8">{{ $application->interview_location }}</div>
                        </div>
                        @if($application->interview_instructions)
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Instructions:</div>
                            <div class="col-md-8">{{ $application->interview_instructions }}</div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            @else
                <div class="alert alert-warning">
                    No application details found.
                </div>
            @endif

            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="btn-track-another">Back to Home</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>