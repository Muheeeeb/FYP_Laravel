@extends('layouts.hr')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4 py-4" style="background: #f6f8fb; min-height: 100vh;">
    <div class="row justify-content-center mb-4">
        <div class="col-12">
            <div class="header mb-4">
                <h1 class="fw-bold" style="font-size:2.2rem;">Welcome, {{ auth()->user()->name }}</h1>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <!-- Statistics Cards -->
            <div class="row g-4 justify-content-center align-items-stretch mb-4">
                <div class="col-12 col-sm-6 col-md-3 d-flex">
                    <div class="card shadow-sm flex-fill border-0 h-100 hover-shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-2">Total Job Postings</h5>
                            <div class="display-5 fw-bold mb-1">{{ $totalJobs }}</div>
                            <p class="text-muted mb-0">Active and closed positions</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 d-flex">
                    <div class="card shadow-sm flex-fill border-0 h-100 hover-shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-2">Active Jobs</h5>
                            <div class="display-5 fw-bold mb-1">{{ $activeJobs }}</div>
                            <p class="text-muted mb-0">Currently accepting applications</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 d-flex">
                    <div class="card shadow-sm flex-fill border-0 h-100 hover-shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-2">Total Applications</h5>
                            <div class="display-5 fw-bold mb-1">{{ $totalApplications }}</div>
                            <p class="text-muted mb-0">Across all positions</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 d-flex">
                    <div class="card shadow-sm flex-fill border-0 h-100 hover-shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-2">Pending Reviews</h5>
                            <div class="display-5 fw-bold mb-1">{{ $pendingReviews }}</div>
                            <p class="text-muted mb-0">Applications awaiting review</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Job Postings -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Job Postings</h5>
                    <a href="{{ route('hr.job-posting') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i> New Job Posting
                    </a>
                </div>
            <div class="card-body">
                <div class="table-responsive">
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                    <th>Position</th>
                                <th>Department</th>
                                    <th>Posted Date</th>
                                    <th>Applications</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                @forelse($recentJobs as $job)
                                    <tr>
                                        <td>{{ $job->title }}</td>
                                        <td>{{ $job->jobRequest?->department?->name ?? 'N/A' }}</td>
                                        <td>{{ $job->created_at->format('M d, Y') }}</td>
                                        <td>{{ $job->applications_count }}</td>
                                        <td>
                                            <span class="status-badge status-{{ strtolower($job->status ?? 'unknown') }}">
                                                {{ $job->status ?? 'Unknown' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('hr.applications.job', $job->id) }}" 
                                                   class="btn btn-sm btn-info me-2">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#closeJobModal{{ $job->id }}">
                                                    <i class="fas fa-times"></i> Close
                                    </button>
                                            </div>

                                            <!-- Close Job Modal -->
                                            <div class="modal fade" id="closeJobModal{{ $job->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('hr.close.job', $job->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Close Job Posting</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to close this job posting? This will prevent new applications from being submitted.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Close Posting</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                </td>
                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No job postings found.</td>
                                    </tr>
                                @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

            <!-- Recent Applications -->
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Applications</h5>
                    <a href="{{ route('hr.applications') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-list me-2"></i> View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Applicant</th>
                                    <th>Position</th>
                                    <th>Applied Date</th>
                                    <th>Match %</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentApplications->sortByDesc(function($app) {
                                    // First sort by is_ranked (ranked applications first)
                                    // Then sort by match_percentage in descending order
                                    return [$app->is_ranked ? 1 : 0, $app->match_percentage ?? 0];
                                }) as $application)
                                    <tr>
                                        <td>{{ $application->name }}</td>
                                        <td>{{ $application->jobPosting?->title ?? 'N/A' }}</td>
                                        <td>{{ $application->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ number_format($application->match_percentage ?? 0, 1) }}%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ strtolower($application->status ?? 'unknown') }}">
                                                {{ $application->status ?? 'Unknown' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($application->jobPosting)
                                                <a href="{{ route('hr.applications.job', $application->jobPosting->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Review
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="fas fa-eye"></i> No Job
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No recent applications found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection