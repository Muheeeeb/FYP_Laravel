<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOD Analytics</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
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

        .sidebar-logo img {
            width: 40px;
            margin-right: 0.5rem;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: var(--card-shadow);
        }

        .stat-card .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.5rem;
        }

        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .stat-card .stat-label {
            color: #64748b;
            font-size: 0.875rem;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: var(--card-shadow);
        }

        .chart-card h2 {
            margin-bottom: 1rem;
            font-size: 1.25rem;
            color: var(--text-color);
        }

        .chart-container {
            position: relative;
            height: 300px;
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
        
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-logo" style="
            display: flex;
            align-items: center;
            padding: 15px 0;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
            <span style="
                font-size: 24px; 
                font-weight: 500; 
                color: #ffffff;
                font-family: 'Poppins', sans-serif;
                margin-left: 10px;">
                HOD
            </span>
        </div>
        <ul class="nav-links">
            <li><a href="{{ route('hod.dashboard') }}"><i class="fas fa-home"></i>Dashboard</a></li>
            <li><a href="{{ route('hod.candidates') }}"><i class="fas fa-user-tie"></i>Candidates</a></li>
            <li><a href="{{ route('hod.analytics') }}" class="active"><i class="fas fa-chart-bar"></i>Analytics</a></li>
            <li><a href="{{ route('hod.settings') }}"><i class="fas fa-cog"></i>Settings</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Analytics Dashboard</h1>
            <div class="user-info">
              
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <!-- Total Requests Card -->
            <a href="{{ route('hod.requests.all') }}" class="stat-card" style="text-decoration: none;">
                <div class="stat-header">
                    <div class="stat-icon" style="background-color: rgba(37, 99, 235, 0.1); color: #2563eb;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #000000;">{{ $statistics['total'] }}</div>
                <div class="stat-label">Total Requests</div>
            </a>

            <!-- Pending Requests Card -->
            <a href="{{ route('hod.requests.pending') }}" class="stat-card" style="text-decoration: none;">
                <div class="stat-header">
                    <div class="stat-icon" style="background-color: rgba(251, 191, 36, 0.1); color: #fbbf24;">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #000000;">{{ $statistics['pending'] }}</div>
                <div class="stat-label">Pending Requests</div>
            </a>

            <!-- Approved Requests Card -->
            <a href="{{ route('hod.requests.approved') }}" class="stat-card" style="text-decoration: none;">
                <div class="stat-header">
                    <div class="stat-icon" style="background-color: rgba(34, 197, 94, 0.1); color: #22c55e;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #000000;">{{ $statistics['approved'] }}</div>
                <div class="stat-label">Approved by Dean</div>
            </a>

            <!-- Rejected Requests Card -->
            <a href="{{ route('hod.requests.rejected') }}" class="stat-card" style="text-decoration: none;">
                <div class="stat-header">
                    <div class="stat-icon" style="background-color: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #000000;">{{ $statistics['rejected'] }}</div>
                <div class="stat-label">Rejected Requests</div>
            </a>

            <!-- Posted Requests Card -->
            <a href="{{ route('hod.requests.posted') }}" class="stat-card" style="text-decoration: none;">
                <div class="stat-header">
                    <div class="stat-icon" style="background-color: rgba(251, 191, 36, 0.1); color: #fbbf24;">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #000000;">{{ $statistics['posted'] }}</div>
                <div class="stat-label">Posted by HR</div>
            </a>
        </div>

        <!-- Charts Grid -->
        <div class="charts-grid">
            <div class="chart-card">
                <h2>Request Status Distribution</h2>
                <div class="chart-container">
                    <canvas id="statusPieChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h2>Monthly Request Trends</h2>
                <div class="chart-container">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!--    Monthly Statistics Table -->
     
    </main>

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


        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Pie Chart
            const statusCtx = document.getElementById('statusPieChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: ['Pending', 'Approved', 'Rejected'],
                    datasets: [{
                        data: [
                            {{ $statistics['pending'] }},
                            {{ $statistics['approved'] }},
                            {{ $statistics['rejected'] }}
                        ],
                        backgroundColor: [
                            '#fbbf24',
                            '#22c55e',
                            '#ef4444'
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

            // Monthly Trend Chart
            const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
            const monthlyData = {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Total Requests',
                    data: {!! json_encode($monthlyData) !!},
                    borderColor: '#2563eb',
                    tension: 0.4,
                    fill: false
                }]
            };

            new Chart(monthlyCtx, {
                type: 'line',
                data: monthlyData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>