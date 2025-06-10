@extends('layouts.hr')

@section('title', 'Applications')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Applications Overview</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('hr.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Applications</li>
                    </ol>
                </nav>
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

            <div class="card shadow-sm mb-4">
    <div class="card-body">
                <div class="table-responsive">
                        <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                            <th>Department</th>
                                <th>Applicant Name</th>
                                <th>Email</th>
                            <th>Applied On</th>
                                <th>Match %</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                @forelse($applications as $application)
                                    @if(strtolower($application->status) === 'pending' || strtolower($application->status) === 'accepted by hod')
                                        <tr>
                                            <td>
                                                @if($application->jobPosting && $application->jobPosting->jobRequest && $application->jobPosting->jobRequest->department)
                                                    {{ $application->jobPosting->jobRequest->department->name }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                    <td>{{ $application->name }}</td>
                                    <td>{{ $application->email }}</td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                    <td>
                                                @if($application->is_ranked)
                                                    <div class="progress" style="height: 20px; min-width: 100px;">
                                                <div class="progress-bar {{ $application->match_percentage >= 70 ? 'bg-success' : ($application->match_percentage >= 40 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                            style="width: {{ $application->match_percentage }}%">
                                                            {{ number_format($application->match_percentage, 1) }}%
                                                </div>
                                            </div>
                                        @else
                                                    <span class="badge bg-secondary">Not Ranked</span>
                                        @endif
                                    </td>
                                    <td>
                                                <span class="badge bg-{{ strtolower($application->status) === 'pending' ? 'warning' : 'info' }}">
                                                    {{ $application->status }}
                                    </span>
                                </td>
                                <td>
                                                <div class="d-flex gap-2">
                                                    @if($application->profile_summary)
                                                        <button type="button" class="btn btn-info btn-sm" 
                                                data-bs-toggle="modal" 
                                                                data-bs-target="#summaryModal{{ $application->id }}">
                                                            <i class="fas fa-file-alt"></i> Summary
                                        </button>
                                        @endif
                                                    <a href="{{ route('hr.application.resume', $application->id) }}" 
                                                       class="btn btn-primary btn-sm" 
                                                       target="_blank">
                                                        <i class="fas fa-file-pdf"></i> CV
                                                    </a>
                                                    <button type="button" class="btn btn-success btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#scheduleModal{{ $application->id }}">
                                                        <i class="fas fa-calendar"></i> Schedule
                                                    </button>
                                    </div>
                                </td>
                            </tr>

                                        @if($application->profile_summary)
                                        <!-- Profile Summary Modal -->
                                        <div class="modal fade" id="summaryModal{{ $application->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Profile Summary - {{ $application->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-4">
                                                            <h6 class="fw-bold">Match Percentage</h6>
                                                            <div class="progress mb-2" style="height: 25px;">
                                                            <div class="progress-bar {{ $application->match_percentage >= 70 ? 'bg-success' : ($application->match_percentage >= 40 ? 'bg-warning' : 'bg-danger') }}" 
                                                                role="progressbar" 
                                                                    style="width: {{ $application->match_percentage }}%">
                                                                    {{ number_format($application->match_percentage, 1) }}%
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mb-4">
                                                            <h6 class="fw-bold">Profile Summary</h6>
                                                            <div class="p-3 bg-light rounded">
                                                                <p class="mb-0">{{ $application->profile_summary }}</p>
                                                            </div>
                                                        </div>

                                                        @if($application->missing_keywords)
                                                        <div>
                                                            <h6 class="fw-bold">Missing Skills/Keywords</h6>
                                                            <div class="p-3 bg-light rounded">
                                                                @foreach(json_decode($application->missing_keywords) as $keyword)
                                                                    <span class="badge bg-warning text-dark me-2">{{ $keyword }}</span>
                                                                @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                            @endif

                                        <!-- Schedule Interview Modal -->
                                        <div class="modal fade" id="scheduleModal{{ $application->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                    <div class="modal-content">
                                                    <form action="{{ route('hr.schedule-interview', $application->id) }}" method="POST">
                                                        @csrf
                                        <div class="modal-header">
                                                            <h5 class="modal-title">Schedule Interview - {{ $application->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="interview_date" class="form-label">Interview Date</label>
                                                                <input type="date" class="form-control" 
                                                                       name="interview_date" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="interview_time" class="form-label">Interview Time</label>
                                                                <input type="time" class="form-control" 
                                                                       name="interview_time" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="interview_location" class="form-label">Location/Meeting Link</label>
                                                                <input type="text" class="form-control" 
                                                                       name="interview_location" required
                                                                       placeholder="Enter physical location or virtual meeting link">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="interview_instructions" class="form-label">Additional Instructions</label>
                                                                <textarea name="interview_instructions" class="form-control" rows="3" 
                                                                          placeholder="Enter any additional instructions for the candidate"></textarea>
                                                </div>
                                                    </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-success">Schedule Interview</button>
                                                    </div>
                                                    </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">No applications found.</div>
                                        </td>
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

<style>
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .progress {
        border-radius: 15px;
    }
    
    .progress-bar {
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .modal-header {
        background-color: #f8f9fa;
    }
    
    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.8em;
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }

    .breadcrumb {
        background-color: transparent;
        padding: 0;
        margin: 0;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
    }

    .h3 {
        color: #5a5c69;
    }
</style>

@push('scripts')
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all modals
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        new bootstrap.Modal(modal);
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
        });
    </script>
@endpush 
@endsection