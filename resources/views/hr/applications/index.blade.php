@extends('layouts.hr')

@section('title', 'Applications')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Job Applications</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter me-2"></i>Filter
            </button>
            <a href="{{ route('hr.applications.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>Export to Excel
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Applications Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Position</th>
                            <th>Applicant Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Match %</th>
                            <th>Resume</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications->sortByDesc(function($app) {
                            return [$app->is_ranked ? 1 : 0, $app->match_percentage ?? 0];
                        }) as $application)
                            <tr>
                                <td>{{ $application->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @if($application->jobPosting)
                                        <a href="{{ route('hr.applications.job', $application->jobPosting->id) }}">
                                            {{ $application->jobPosting->title }}
                                        </a>
                                    @else
                                        <span class="text-muted">No Job</span>
                                    @endif
                                </td>
                                <td>{{ $application->name }}</td>
                                <td>{{ $application->email }}</td>
                                <td>{{ $application->phone }}</td>
                                <td>
                                    @if($application->is_ranked)
                                        <div class="progress">
                                            <div class="progress-bar {{ $application->match_percentage >= 70 ? 'bg-success' : ($application->match_percentage >= 40 ? 'bg-warning' : 'bg-danger') }}" 
                                                role="progressbar" 
                                                style="width: {{ $application->match_percentage }}%"
                                                aria-valuenow="{{ $application->match_percentage }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                                {{ number_format($application->match_percentage, 1) }}%
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Not ranked</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('hr.application.resume', $application->id) }}" 
                                        class="btn btn-sm btn-outline-primary"
                                        target="_blank">
                                        <i class="fas fa-file-pdf me-1"></i>View
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-{{ strtolower($application->status) === 'accepted by hod' ? 'info' : (strtolower($application->status) === 'rejected by hod' ? 'danger' : (strtolower($application->status) === 'interview scheduled' ? 'warning' : (strtolower($application->status) === 'interviewed' ? 'primary' : (strtolower($application->status) === 'hired' ? 'success' : 'secondary')))) }}">
                                        {{ $application->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        @if(strtolower($application->status) === 'accepted by hod')
                                            <button type="button" 
                                                class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#scheduleInterviewModal{{ $application->id }}">
                                                <i class="fas fa-calendar-alt me-1"></i>Schedule Interview
                                            </button>
                                        @endif
                                        
                                        @if($application->profile_summary)
                                            <button type="button" 
                                                class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#summaryModal{{ $application->id }}">
                                                <i class="fas fa-file-alt me-1"></i>Summary
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Summary Modal -->
                            @if($application->profile_summary)
                            <div class="modal fade" id="summaryModal{{ $application->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $application->name }}'s Profile Summary</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="card mb-3">
                                                <div class="card-header bg-primary text-white">
                                                    <h6 class="mb-0">Match Score: {{ number_format($application->match_percentage, 1) }}%</h6>
                                                </div>
                                                <div class="card-body">
                                                    <h6 class="fw-bold mb-3">Profile Summary</h6>
                                                    <div class="p-3 bg-light rounded">
                                                        {!! nl2br(e($application->profile_summary)) !!}
                                                    </div>
                                                    
                                                    @if(isset($application->missing_keywords) && $application->missing_keywords)
                                                    <h6 class="fw-bold mt-4 mb-2">Missing Keywords</h6>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach(json_decode($application->missing_keywords) as $keyword)
                                                            <span class="badge bg-warning text-dark">{{ $keyword }}</span>
                                                        @endforeach
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Schedule Interview Modal -->
                            @if(strtolower($application->status) === 'accepted by hod')
                            <div class="modal fade" id="scheduleInterviewModal{{ $application->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Schedule Interview - {{ $application->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('hr.application.status', $application->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="interview_date" class="form-label">Interview Date</label>
                                                    <input type="date" class="form-control" id="interview_date" name="interview_date" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="interview_time" class="form-label">Interview Time</label>
                                                    <input type="time" class="form-control" id="interview_time" name="interview_time" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="interview_location" class="form-label">Location</label>
                                                    <input type="text" class="form-control" id="interview_location" name="interview_location" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="feedback" class="form-label">Additional Notes</label>
                                                    <textarea class="form-control" id="feedback" name="feedback" rows="3"></textarea>
                                                </div>
                                                <input type="hidden" name="status" value="Interview Scheduled">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Schedule Interview</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-folder-open text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No applications found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Applications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('hr.applications') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All</option>
                                    <option value="Accepted by HOD" {{ request('status') == 'Accepted by HOD' ? 'selected' : '' }}>Shortlisted by HOD</option>
                                    <option value="Interview Scheduled" {{ request('status') == 'Interview Scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                                    <option value="Interviewed" {{ request('status') == 'Interviewed' ? 'selected' : '' }}>Interviewed</option>
                                    <option value="Hired" {{ request('status') == 'Hired' ? 'selected' : '' }}>Hired</option>
                                    <option value="Rejected by HOD" {{ request('status') == 'Rejected by HOD' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Position</label>
                                <select name="position" class="form-select">
                                    <option value="">All Positions</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->id }}" {{ request('position') == $position->id ? 'selected' : '' }}>
                                            {{ $position->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection