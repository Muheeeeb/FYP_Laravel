@extends('layouts.hr')

@section('title', 'Manage Jobs')

@section('content')
        <div class="header">
    <h1>Manage Jobs</h1>
        </div>

        @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Pending Jobs Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Pending Jobs</h5>
    </div>
    <div class="card-body">
        @if($pendingJobs->isEmpty())
            <div class="text-center py-4">
                <p class="text-muted mb-0">No pending job requests found.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Requested By</th>
                            <th>Approved Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingJobs as $job)
                            <tr>
                                <td>{{ $job->position }}</td>
                                <td>{{ optional($job->department)->name ?? 'N/A' }}</td>
                                <td>{{ optional($job->hod)->name ?? 'N/A' }}</td>
                                <td>
                                    @if($job->approved_by_dean_at)
                                        @if(is_string($job->approved_by_dean_at))
                                            {{ $job->approved_by_dean_at }}
                                        @else
                                            {{ $job->approved_by_dean_at->format('M d, Y') }}
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('hr.post.job', $job->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-paper-plane me-1"></i>Post Job
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Posted Jobs Section -->
        <div class="card">
    <div class="card-header">
        <h5 class="mb-0">Posted Jobs</h5>
    </div>
    <div class="card-body">
        @if($postedJobs->isEmpty())
            <div class="text-center py-4">
                <p class="text-muted mb-0">No posted jobs found.</p>
            </div>
                @else
            <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Department</th>
                                <th>Posted Date</th>
                            <th>Status</th>
                            <th>Applications</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($postedJobs->sortByDesc('created_at') as $job)
                                <tr>
                                    <td>{{ $job->title }}</td>
                                <td>{{ optional(optional($job->jobRequest)->department)->name ?? 'N/A' }}</td>
                                <td>
                                    @if($job->created_at)
                                        @if(is_string($job->created_at))
                                            {{ $job->created_at }}
                                        @else
                                            {{ $job->created_at->format('M d, Y') }}
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($job->status) }}">
                                        {{ $job->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('hr.applications.job', $job->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye me-1"></i>View Applications
                                        </a>
                                    </td>
                                <td>
                                    <div class="btn-group">
                                        @if($job->status === 'Active')
                                            <form action="{{ route('hr.close.job', $job->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to close this job posting?')">
                                                    <i class="fas fa-times-circle me-1"></i>Close
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('hr.delete.job', $job->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this job posting?')">
                                                <i class="fas fa-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
                @endif
            </div>
        </div>
@endsection