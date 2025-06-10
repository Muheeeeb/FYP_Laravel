@extends('layouts.hr')

@section('title', 'Analytics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <div class="header">
                <h1>Analytics Overview</h1>
        </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Total Applications</h5>
                            <p class="card-text display-6">{{ $totalApplications }}</p>
                            <p class="text-muted">All time applications</p>
                        </div>
                    </div>
                        </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Active Jobs</h5>
                            <p class="card-text display-6">{{ $activeJobs }}</p>
                            <p class="text-muted">Currently open positions</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Average Match %</h5>
                            <p class="card-text display-6">{{ number_format($averageMatch, 1) }}%</p>
                            <p class="text-muted">Across all applications</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Hired Candidates</h5>
                            <p class="card-text display-6">{{ $hiredCandidates }}</p>
                            <p class="text-muted">Successfully placed</p>
                    </div>
                    </div>
                </div>
            </div>

            <!-- Department Analysis -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Department Analysis</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Department</th>
                                    <th>Open Positions</th>
                                    <th>Total Applications</th>
                                    <th>Avg Match %</th>
                                    <th>Hired</th>
                                    <th>In Process</th>
                        </tr>
                    </thead>
                    <tbody>
                                @foreach($departmentStats as $stat)
                                <tr>
                                    <td>{{ $stat->department_name }}</td>
                                    <td>{{ $stat->open_positions }}</td>
                                    <td>{{ $stat->total_applications }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ number_format($stat->average_match, 1) }}%
                                        </span>
                            </td>
                                    <td>{{ $stat->hired_count }}</td>
                                    <td>{{ $stat->in_process_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                    </div>
                </div>
            </div>

            <!-- Application Timeline -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Application Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                    <thead>
                        <tr>
                                    <th>Date</th>
                            <th>Position</th>
                            <th>Department</th>
                                    <th>Applications</th>
                                    <th>Avg Match %</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                                @foreach($applicationTimeline as $entry)
                                <tr>
                                    <td>{{ $entry->date->format('M d, Y') }}</td>
                                    <td>{{ $entry->position }}</td>
                                    <td>{{ $entry->department_name }}</td>
                                    <td>{{ $entry->applications_count }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ number_format($entry->average_match, 1) }}%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($entry->status) }}">
                                            {{ $entry->status }}
                                        </span>
                                    </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                    </div>
                </div>
            </div>

            <!-- Match Score Distribution -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Match Score Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                    <thead>
                        <tr>
                                    <th>Range</th>
                                    <th>Count</th>
                                    <th>Percentage</th>
                                    <th>Hired</th>
                                    <th>Rejected</th>
                        </tr>
                    </thead>
                    <tbody>
                                @foreach($matchDistribution as $range)
                                <tr>
                                    <td>{{ $range->range }}</td>
                                    <td>{{ $range->count }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-info" 
                                                 role="progressbar" 
                                                 style="width: {{ number_format($range->percentage, 1) }}%"
                                                 aria-valuenow="{{ number_format($range->percentage, 1) }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                {{ number_format($range->percentage, 1) }}%
                                            </div>
                                        </div>
                            </td>
                                    <td>{{ $range->hired_count }}</td>
                                    <td>{{ $range->rejected_count }}</td>
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
@endsection