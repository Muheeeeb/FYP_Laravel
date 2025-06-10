@extends('layouts.hod')

@section('title', 'Analytics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="header mb-4">
                <h1>Analytics Dashboard</h1>
                <div class="header-actions">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
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

                <div class="col-md-3">
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

                <div class="col-md-3">
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

                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="stat-icon bg-danger-light">
                                    <i class="fas fa-times-circle text-danger"></i>
                                </div>
                            </div>
                            <h3 class="stat-value mb-1">{{ $rejectedCount }}</h3>
                            <p class="stat-label mb-0">Rejected Requests</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Monthly Request Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px;">
                                <canvas id="monthlyStatsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Status Distribution</h5>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px;">
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
                                    <th>Position</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivity as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format('M d, Y') }}</td>
                                    <td>{{ $activity->position }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($activity->status) }}">
                                            {{ $activity->status }}
                                        </span>
                                    </td>
                                    <td>{{ $activity->action }}</td>
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
    <script>
    // Monthly Statistics Chart
    const monthlyCtx = document.getElementById('monthlyStatsChart').getContext('2d');
    const monthlyData = @json($monthlyStats);
    
    new Chart(monthlyCtx, {
        type: 'line',
                data: {
            labels: monthlyData.map(item => {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                return months[item.month - 1];
            }),
                    datasets: [{
                label: 'Job Requests',
                data: monthlyData.map(item => item.total),
                borderColor: '#2563eb',
                tension: 0.3,
                fill: true,
                backgroundColor: 'rgba(37, 99, 235, 0.1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                    display: false
                }
            },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
            }
        }
    });

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
    const statusStats = @json($statusStats);
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Approved', 'Rejected', 'Posted'],
            datasets: [{
                data: [
                    statusStats.pending_requests,
                    statusStats.approved_requests,
                    statusStats.rejected_requests,
                    statusStats.posted_requests
                ],
                backgroundColor: [
                    '#fbbf24',
                    '#22c55e',
                    '#ef4444',
                    '#3b82f6'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
        });
    </script>
@endpush

@endsection