@extends('layouts.dean')

@section('title', 'Analytics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="header mb-4">
                <h1>Analytics Overview</h1>
                <div class="header-actions">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="stat-icon bg-primary-light">
                                    <i class="fas fa-file-alt text-primary"></i>
                                </div>
                            </div>
                            <h3 class="stat-value mb-1">{{ $totalRequests }}</h3>
                            <p class="stat-label mb-0">Total Requests</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="stat-icon bg-success-light">
                                    <i class="fas fa-check-circle text-success"></i>
                                </div>
                            </div>
                            <h3 class="stat-value mb-1">{{ $approvedCount }}</h3>
                            <p class="stat-label mb-0">Approved Requests</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="stat-icon bg-warning-light">
                                    <i class="fas fa-clock text-warning"></i>
                                </div>
                            </div>
                            <h3 class="stat-value mb-1">{{ $pendingCount }}</h3>
                            <p class="stat-label mb-0">Pending Requests</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Analysis -->
            <div class="row mb-4">
                <div class="col-lg-7">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Department Analysis</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            <th>Total Requests</th>
                                            <th>Approved</th>
                                            <th>Pending</th>
                                            <th>Rejected</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($departmentStats as $stat)
                                        <tr>
                                            <td>{{ $stat->department_name }}</td>
                                            <td>{{ $stat->total_requests }}</td>
                                            <td>
                                                <span class="status-badge status-approved">
                                                    {{ $stat->approved_requests }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge status-pending">
                                                    {{ $stat->pending_requests }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge status-rejected">
                                                    {{ $stat->rejected_requests }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @if(count($departmentStats) == 0)
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    No department data available
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Status Distribution</h5>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div style="height: 250px; width: 100%;">
                                <canvas id="statusDistributionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivity as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format('M d, Y') }}</td>
                                    <td>{{ $activity->department_name }}</td>
                                    <td>{{ $activity->position }}</td>
                                    <td>{{ $activity->action }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($activity->status) }}">
                                            {{ $activity->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <style>
    :root {
        --primary-color: #2563eb;
        --secondary-color: #1e40af;
        --background-color: #f1f5f9;
        --text-color: #334155;
        --white: #ffffff;
        --card-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    }

    .card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-primary-light {
        background-color: #dbeafe;
    }

    .bg-warning-light {
        background-color: #fef3c7;
    }

    .bg-success-light {
        background-color: #dcfce7;
    }

    .bg-danger-light {
        background-color: #fee2e2;
    }

    .text-primary {
        color: var(--primary-color);
    }

    .text-warning {
        color: #92400e;
    }

    .text-success {
        color: #166534;
    }

    .text-danger {
        color: #991b1b;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-color);
    }

    .stat-label {
        color: #64748b;
        font-size: 0.875rem;
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

    .status-posted {
        background-color: #dbeafe;
        color: #1e40af;
    }
    </style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Status Distribution Chart
    const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Approved', 'Rejected', 'Posted'],
            datasets: [{
                data: [
                    {{ $pendingCount }},
                    {{ $approvedCount }},
                    {{ $rejectedCount }},
                    {{ $postedCount }}
                ],
                backgroundColor: [
                    '#fbbf24', // warning/pending
                    '#22c55e', // success/approved
                    '#ef4444', // danger/rejected
                    '#3b82f6'  // primary/posted
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });
    </script>
@endpush

@endsection