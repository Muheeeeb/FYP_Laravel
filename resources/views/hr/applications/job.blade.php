@extends('layouts.hr')

@section('title', 'Job Applications')

@section('content')
<div class="container-fluid">
    <div class="header">
        <h1>{{ $jobPosting->title }} - Applications</h1>
        <div>
            <a href="{{ route('hr.applications') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to All Applications
            </a>
            <a href="{{ route('hr.applications.refresh-ranking', $jobPosting->id) }}" class="btn btn-primary">
                <i class="fas fa-sync-alt"></i> Refresh Ranking
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Applications for {{ $jobPosting->title }}</h4>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter"></i> Filter Applications
                </button>
            </div>
        </div>

        <div class="card-body">
            @if($applications->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5>No applications found</h5>
                    <p class="text-muted">There are no applications for this position yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Match %</th>
                                <th>Profile</th>
                                <th>Resume</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications->sortByDesc(function($app) {
                                // First sort by is_ranked (ranked applications first)
                                // Then sort by match_percentage in descending order
                                return [$app->is_ranked ? 1 : 0, $app->match_percentage ?? 0];
                            }) as $application)
                                <tr>
                                    <td>{{ $application->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $application->name }}</td>
                                    <td>{{ $application->email }}</td>
                                    <td>{{ $application->phone }}</td>
                                    <td>
                                        @if($application->is_ranked)
                                            <div class="progress">
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
                                        <div class="d-flex gap-1">
                                            <button type="button" 
                                                    class="btn btn-sm btn-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#profileModal{{ $application->id }}">
                                                View Profile
                                            </button>
                                            @if($application->is_ranked && $application->profile_summary)
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#summaryModal{{ $application->id }}">
                                                View Summary
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('hr.application.resume', $application->id) }}" 
                                           class="btn btn-sm btn-primary"
                                           target="_blank">
                                            View CV
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match(strtolower($application->status)) {
                                                'rejected' => 'danger',
                                                'interview scheduled' => 'primary',
                                                'pending' => 'warning',
                                                'hired' => 'success',
                                                'applied' => 'info',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                </tr>

                                <!-- Profile Modal -->
                                <div class="modal fade" id="profileModal{{ $application->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ $application->name }}'s Profile</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Contact Information</h6>
                                                        <p><strong>Email:</strong> {{ $application->email }}</p>
                                                        <p><strong>Phone:</strong> {{ $application->phone }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Application Details</h6>
                                                        <p><strong>Applied On:</strong> {{ $application->created_at->format('M d, Y') }}</p>
                                                        <p><strong>Status:</strong> {{ ucfirst($application->status) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Summary Modal -->
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
                                                        
                                                        @if(isset($application->missing_keywords) && $application->missing_keywords && is_string($application->missing_keywords))
                                                        <h6 class="fw-bold mt-4 mb-2">Missing Keywords</h6>
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @php
                                                                $keywords = json_decode($application->missing_keywords);
                                                            @endphp
                                                            @if($keywords && is_array($keywords))
                                                                @foreach($keywords as $keyword)
                                                                    <span class="badge bg-warning text-dark">{{ $keyword }}</span>
                                                                @endforeach
                                                            @endif
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
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
                <form id="filterForm">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="shortlisted">Shortlisted</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Match Percentage</label>
                        <select class="form-select" name="match">
                            <option value="">All</option>
                            <option value="70">70% and above</option>
                            <option value="50">50% and above</option>
                            <option value="30">30% and above</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle status updates
    $('.status-update').change(function() {
        const applicationId = $(this).data('id');
        const newStatus = $(this).val();
        const select = $(this);

        $.ajax({
            url: /hr/applications/${applicationId}/status,
            method: 'POST',
            data: {
                status: newStatus
            },
            success: function(response) {
                if (response.success) {
                    // Update the badge color
                    const badge = select.closest('tr').find('.badge');
                    badge.removeClass('bg-primary bg-success bg-danger');
                    if (newStatus === 'pending') {
                        badge.addClass('bg-primary');
                    } else if (newStatus === 'shortlisted') {
                        badge.addClass('bg-success');
                    } else {
                        badge.addClass('bg-danger');
                    }
                    badge.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                    
                    // Show success message
                    alert('Status updated successfully');
                }
            },
            error: function(xhr) {
                alert('Error updating status: ' + xhr.responseJSON.message);
                // Reset the select to its previous value
                select.val(select.find('option[selected]').val());
            }
        });
    });
});
</script>
@endpush
@endsection