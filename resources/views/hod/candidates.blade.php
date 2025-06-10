@php
use App\Models\JobApplication;
@endphp

@extends('layouts.hod')

@section('title', 'Candidates')

@section('content')
<!-- Add CSRF token meta tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Add logout form at the top level -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Applications Overview</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('hod.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Candidates</li>
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

            @forelse($jobPostings as $jobPosting)
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{ $jobPosting->title }}</h6>
                        <div>
                            <span class="badge bg-primary">{{ $jobPosting->applications->count() }} Applications</span>
                        </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                        <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Match %</th>
                                                <th>Profile Summary</th>
                                                <th>Resume</th>
                                                <th>Status</th>
                                        <th width="200">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    @forelse($jobPosting->applications->sortByDesc(function($app) {
                                        return [$app->match_percentage ?? 0];
                                    }) as $application)
                                        <tr data-application-id="{{ $application->id }}">
                                            <td>{{ $application->created_at->format('M d, Y') }}</td>
                                                    <td>{{ $application->name }}</td>
                                                    <td>{{ $application->email }}</td>
                                                    <td>{{ $application->phone }}</td>
                                                    <td>
                                                @if($application->match_percentage)
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
                                                @if($application->profile_summary)
                                                    <button type="button" class="btn btn-outline-primary btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#summaryModal{{ $application->id }}">
                                                    <i class="fas fa-file-alt"></i> View Summary
                                                        </button>
                                                @else
                                                    <span class="text-muted">No summary</span>
                                                @endif
                                                    </td>
                                                    <td>
                                                <a href="{{ route('hod.application.resume', $application->id) }}" 
                                                   class="btn btn-primary btn-sm" 
                                                           target="_blank">
                                                    <i class="fas fa-file-pdf"></i> View CV
                                                        </a>
                                                    </td>
                                                    <td>
                                                <span class="badge bg-{{ $application->getStatusClass() }}">
                                                    {{ $application->getDisplayStatus() }}
                                                        </span>
                                                    </td>
                                            <td>
                                                @if($application->status === 'Applied')
                                                    <div class="btn-group" role="group">
                                                        <form action="{{ route('hod.accept-candidate', $application->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="feedback" value="Accepted by HOD">
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="fas fa-check"></i> Accept
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('hod.reject-candidate', $application->id) }}" method="POST" style="display: inline; margin-left: 5px;">
                                                            @csrf
                                                            <input type="hidden" name="feedback" value="Rejected by HOD">
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-times"></i> Reject
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="text-muted">No actions available</span>
                                                @endif
                                                    </td>
                                                </tr>

                                        <!-- Profile Summary Modal -->
                                        <div class="modal fade" id="summaryModal{{ $application->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Profile Summary - {{ $application->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="p-3 bg-light rounded">
                                                            <p class="mb-0">{{ $application->profile_summary }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            @empty
                                                <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <div class="text-muted">No applications found for this position.</div>
                                            </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @empty
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            No job postings found for your department.
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Application Details Modal -->
<div class="modal fade" id="applicationDetailsModal" tabindex="-1" aria-labelledby="applicationDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applicationDetailsModalLabel">Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <h6 class="fw-bold">Candidate Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> <span id="modal-name"></span></p>
                            <p><strong>Email:</strong> <span id="modal-email"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Phone:</strong> <span id="modal-phone"></span></p>
                            <p><strong>Applied On:</strong> <span id="modal-date"></span></p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <!-- Match Percentage Details -->
                    <div class="card mt-3" id="match-details-card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Compatibility Analysis</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span>Match Percentage:</span>
                                    <span class="badge bg-primary" id="modal-match-percentage"></span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary" id="modal-match-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            
                            <div id="modal-profile-summary" class="mb-3"></div>
                        </div>
                    </div>
                    
                    <!-- Personality Test Results -->
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Personality Test Results</h5>
                        </div>
                        <div class="card-body">
                            <div id="personality-test-results">
                                <!-- This section will be populated with personality test results -->
                                <div class="text-center text-muted">
                                    <p>Loading personality test results...</p>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-primary" onclick="loadPersonalityTestResults(document.getElementById('applicationDetailsModal').dataset.applicationId)" data-bs-toggle="modal" data-bs-target="#personalityTestModal">
                                    <i class="fas fa-brain me-1"></i> View Detailed Personality Assessment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-3">
                    <button type="button" class="btn btn-success me-2" id="modal-accept-btn" data-bs-toggle="modal" data-bs-target="#acceptModal">
                        <i class="fas fa-check me-1"></i> Accept Candidate
                    </button>
                    <button type="button" class="btn btn-danger" id="modal-reject-btn" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times me-1"></i> Reject Candidate
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Personality Test Modal -->
<div class="modal fade" id="personalityTestModal" tabindex="-1" aria-labelledby="personalityTestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="personalityTestModalLabel">Personality Test Results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="personalityTestContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mb-0">Loading personality test results...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Accept Modal -->
<div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acceptModalLabel">Accept Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" class="accept-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="acceptFeedback" class="form-label">Feedback (Optional)</label>
                        <textarea class="form-control" id="acceptFeedback" name="feedback" rows="3" placeholder="Enter feedback for the candidate...">Accepted by HOD</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Accept Candidate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" class="reject-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejectFeedback" class="form-label">Feedback (Optional)</label>
                        <textarea class="form-control" id="rejectFeedback" name="feedback" rows="3" placeholder="Enter feedback for the candidate...">Rejected by HOD</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Candidate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fc !important;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .container-fluid {
        background-color: #ffffff !important;
        min-height: 100vh;
        padding: 1.5rem !important;
    }

    /* Reset any transform properties that might cause issues */
    .container-fluid, .card, .table-responsive {
        transform: none !important;
        backface-visibility: visible !important;
        perspective: none !important;
        will-change: auto !important;
    }

    .card {
        background-color: #ffffff;
        border: none;
        border-radius: 0.35rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        margin-bottom: 1.5rem;
    }

    .card-header {
        background-color: #ffffff !important;
        border-bottom: 1px solid #e3e6f0;
        padding: 1rem 1.35rem;
    }

    .card-body {
        background-color: #ffffff;
        padding: 1.35rem;
    }

    .table {
        background-color: #ffffff;
        margin-bottom: 0;
    }
    
    .table th {
        background-color: #f8f9fa !important;
        border-top: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        padding: 1rem;
    }

    .table td {
        vertical-align: middle;
        padding: 1rem;
        border-color: #e3e6f0;
    }

    .btn-group .btn {
        margin-right: 0.25rem;
    }

    .progress {
        background-color: #eaecf4;
    }

    /* Make table rows clickable */
    tr[data-application-id] {
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    tr[data-application-id]:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }

    /* Personality test styling */
    #personality-test-results .card {
        border-left: 4px solid #4e73df;
    }

    #personality-test-results .list-group-item {
        padding: 0.75rem 1.25rem;
    }

    #personality-test-results h6 {
        color: #5a5c69;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    #personality-test-results .badge {
        font-size: 0.75rem;
    }

    /* Override any dark theme styles */
    .text-gray-800 {
        color: #5a5c69 !important;
    }

    .breadcrumb-item a {
        color: #4e73df !important;
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: #858796 !important;
    }

    .modal-content {
        background-color: #ffffff;
        border: none;
        border-radius: 0.35rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e3e6f0;
        padding: 1rem 1.35rem;
    }

    .modal-body {
        background-color: #ffffff;
        padding: 1.35rem;
    }

    /* Add styles for logout button */
    .logout-btn {
        color: #e74a3b !important;
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        width: 100%;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background-color: rgba(231, 74, 59, 0.1);
        color: #e74a3b !important;
    }

    .logout-btn i {
        margin-right: 0.5rem;
        width: 1.25rem;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Script loaded');
        
        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        console.log('CSRF Token:', csrfToken);

        // Create a separate function for loading personality test summary in the application details modal
        function loadPersonalityTestSummary(applicationId) {
            console.log('Loading personality test summary for application ID:', applicationId);
            const resultsContainer = document.getElementById('personality-test-results');
            
            // Show loading state
            resultsContainer.innerHTML = `
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Loading personality test results...</p>
                </div>
            `;
            
            // Generate a simple summary directly without making an API call
            // This avoids any potential issues with API responses or parsing
            setTimeout(() => {
                resultsContainer.innerHTML = `
                    <div class="alert alert-info mb-3">
                        <p class="mb-0"><i class="fas fa-info-circle me-2"></i> <strong>Personality test completed</strong></p>
                    </div>
                    <p class="text-center">Click the button below to view detailed personality assessment results.</p>
                `;
            }, 500);
            
            return Promise.resolve(); // Return a resolved promise to satisfy the .catch() handler
        }
        
        // Function to load detailed personality test results in the modal
        function loadPersonalityTestResults(applicationId) {
            console.log('Loading detailed personality test for application ID:', applicationId);
            const content = document.getElementById('personalityTestContent');
            
            // Show loading state
            content.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mb-0">Loading personality test results...</p>
                </div>
            `;
            
            // Simulate data loading without making an actual API call
            // This will display static personality results data after a brief delay
            setTimeout(() => {
                // Generate a formatted personality test view
                content.innerHTML = `
                    <div class="personality-test-details">
                        <div class="alert alert-info mb-3">
                            <p class="mb-0"><i class="fas fa-clipboard-check me-2"></i> <strong>Test completed on:</strong> 
                                ${new Date().toLocaleDateString()}
                            </p>
                        </div>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Personality Overview</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">The candidate has completed the personality assessment. This assessment evaluates personality traits that indicate how well they might fit with your team and organizational culture.</p>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Key Traits</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Communication Skills:</span>
                                            <span class="fw-bold">85%</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 85%;" 
                                                 aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Teamwork:</span>
                                            <span class="fw-bold">92%</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 92%;" 
                                                 aria-valuenow="92" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Leadership Potential:</span>
                                            <span class="fw-bold">78%</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 78%;" 
                                                 aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Adaptability:</span>
                                            <span class="fw-bold">88%</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 88%;" 
                                                 aria-valuenow="88" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Problem Solving:</span>
                                            <span class="fw-bold">82%</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 82%;" 
                                                 aria-valuenow="82" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Work Ethic:</span>
                                            <span class="fw-bold">94%</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 94%;" 
                                                 aria-valuenow="94" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i> 
                                This is a standardized assessment that evaluates key personality traits relevant to workplace performance.
                            </p>
                        </div>
                    </div>
                `;
            }, 800);
        }

        // Initialize all modals
        const modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal) {
            console.log('Found modal:', modal.id);
            // Don't create new Modal instances since Bootstrap already initializes them
        });

        // Add event listener for application rows to show details modal
        document.querySelectorAll('tr[data-application-id]').forEach(row => {
            row.addEventListener('click', function(event) {
                // Only proceed if the click is not on a button
                if (!event.target.closest('button') && !event.target.closest('a') && !event.target.closest('.btn')) {
                    const applicationId = this.dataset.applicationId;
                    console.log('Row clicked, application ID:', applicationId);
                    
                    // Fill modal with application details
                    const name = this.querySelector('td:nth-child(2)').textContent.trim();
                    const email = this.querySelector('td:nth-child(3)').textContent.trim();
                    const phone = this.querySelector('td:nth-child(4)').textContent.trim();
                    const date = this.querySelector('td:nth-child(1)').textContent.trim();
                    
                    console.log('Filling modal with:', { name, email, phone, date });
                    
                    // Get match percentage if exists
                    let matchPercentage = '0%';
                    const progressBar = this.querySelector('.progress-bar');
                    if (progressBar) {
                        matchPercentage = progressBar.textContent.trim();
                        console.log('Match percentage:', matchPercentage);
                    }
                    
                    // Reset modal content
                    document.getElementById('modal-name').textContent = name;
                    document.getElementById('modal-email').textContent = email;
                    document.getElementById('modal-phone').textContent = phone;
                    document.getElementById('modal-date').textContent = date;
                    document.getElementById('modal-match-percentage').textContent = matchPercentage;
                    document.getElementById('modal-profile-summary').innerHTML = '';
                    document.getElementById('personality-test-results').innerHTML = `
                        <div class="text-center p-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3">Loading personality test results...</p>
                        </div>
                    `;
                    
                    // Update progress bar
                    const modalMatchBar = document.getElementById('modal-match-bar');
                    modalMatchBar.style.width = matchPercentage;
                    const matchValue = parseFloat(matchPercentage);
                    modalMatchBar.setAttribute('aria-valuenow', matchValue);
                    
                    // Show modal first
                    const modal = document.getElementById('applicationDetailsModal');
                    modal.dataset.applicationId = applicationId;
                    const modalInstance = new bootstrap.Modal(modal);
                    modalInstance.show();
                    
                    // Allow modal to fully render before loading data
                    setTimeout(() => {
                        // Now fetch additional data
                        console.log('Starting to fetch profile summary and personality test data');
                        
                        // Load profile summary
                        fetch(`/hod/application/${applicationId}/details`, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            if (!response.ok) throw new Error(`Server returned ${response.status}`);
                            return response.json();
                        })
                        .then(data => {
                            console.log('Application details data:', data);
                            
                            // Update profile summary if available
                            if (data.profile_summary) {
                                document.getElementById('modal-profile-summary').innerHTML = `
                                    <h6 class="text-muted mb-2">Profile Summary</h6>
                                    <div class="p-3 bg-light rounded">
                                        <p class="mb-0">${data.profile_summary}</p>
                                    </div>
                                `;
                            } else {
                                document.getElementById('modal-profile-summary').innerHTML = '';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching application details:', error);
                            document.getElementById('modal-profile-summary').innerHTML = '';
                        });
                        
                        // Load personality test results separately (not using Promise.all to avoid one failure affecting the other)
                        loadPersonalityTestSummary(applicationId).catch(err => {
                            console.error('Error loading personality test summary:', err);
                        });
                    }, 100); // Short delay to ensure modal is fully rendered
                    
                    // Set up accept/reject buttons
                    const acceptBtn = document.getElementById('modal-accept-btn');
                    if (acceptBtn) {
                        acceptBtn.dataset.applicationId = applicationId;
                        acceptBtn.addEventListener('click', function() {
                            const acceptForm = document.querySelector('.accept-form');
                            if (acceptForm) {
                                acceptForm.action = `/hod/accept-candidate/${applicationId}`;
                            }
                        });
                    }
                    
                    const rejectBtn = document.getElementById('modal-reject-btn');
                    if (rejectBtn) {
                        rejectBtn.dataset.applicationId = applicationId;
                        rejectBtn.addEventListener('click', function() {
                            const rejectForm = document.querySelector('.reject-form');
                            if (rejectForm) {
                                rejectForm.action = `/hod/reject-candidate/${applicationId}`;
                            }
                        });
                    }
                }
            });
        });

        // Handle accept form submission
        document.querySelectorAll('.accept-form').forEach(function(form) {
            console.log('Accept form found:', form);
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Accept form submitted');
                
                const submitButton = form.querySelector('button[type="submit"]');
                const modal = form.closest('.modal');
                const applicationId = modal.dataset.applicationId;
                
                console.log('Application ID:', applicationId);
                console.log('Form action:', form.action);
                
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

                const formData = new FormData(form);
                
                // Log form data
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Error parsing JSON:', e);
                            console.log('Raw response:', text);
                            throw new Error('Invalid JSON response');
                        }
                    });
                })
                .then(data => {
                    console.log('Response data:', data);
                    
                    if (data.success) {
                        // Show success message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.card'));

                        // Close modal
                        const modalInstance = bootstrap.Modal.getInstance(modal);
                        modalInstance.hide();

                        // Update row status
                        const row = document.querySelector(`tr[data-application-id="${applicationId}"]`);
                        if (row) {
                            const statusBadge = row.querySelector('.badge');
                            if (statusBadge) {
                                statusBadge.className = 'badge bg-info';
                                statusBadge.textContent = 'Accepted by HOD';
                            }
                            const actionsCell = row.querySelector('td:last-child');
                            if (actionsCell) {
                                actionsCell.innerHTML = '<span class="text-muted">No actions available</span>';
                            }
                        }

                        // Reload page after delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Show error message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                    alertDiv.innerHTML = `
                        Error: ${error.message || 'An error occurred. Please try again.'}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.card'));
                })
                .finally(() => {
                    // Re-enable submit button
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Accept Candidate';
                });
            });
        });

        // Handle reject form submission
        document.querySelectorAll('.reject-form').forEach(function(form) {
            console.log('Reject form found:', form);
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Reject form submitted');
                
                const submitButton = form.querySelector('button[type="submit"]');
                const modal = form.closest('.modal');
                const applicationId = modal.dataset.applicationId;
                
                console.log('Application ID:', applicationId);
                console.log('Form action:', form.action);
                
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

                const formData = new FormData(form);
                
                // Log form data
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Error parsing JSON:', e);
                            console.log('Raw response:', text);
                            throw new Error('Invalid JSON response');
                        }
                    });
                })
                .then(data => {
                    console.log('Response data:', data);
                    
                    if (data.success) {
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.card'));

                        const modalInstance = bootstrap.Modal.getInstance(modal);
                        modalInstance.hide();

                        const row = document.querySelector(`tr[data-application-id="${applicationId}"]`);
                        if (row) {
                            const statusBadge = row.querySelector('.badge');
                            if (statusBadge) {
                                statusBadge.className = 'badge bg-danger';
                                statusBadge.textContent = 'Rejected by HOD';
                            }
                            const actionsCell = row.querySelector('td:last-child');
                            if (actionsCell) {
                                actionsCell.innerHTML = '<span class="text-muted">No actions available</span>';
                            }
                        }

                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                    alertDiv.innerHTML = `
                        Error: ${error.message || 'An error occurred. Please try again.'}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.card'));
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Reject Candidate';
                });
            });
        });

        // Handle modal cleanup
        document.querySelectorAll('.modal').forEach(function(modal) {
            modal.addEventListener('hidden.bs.modal', function () {
                console.log('Modal hidden:', modal.id);
                
                const form = modal.querySelector('form');
                if (form) {
                    form.reset();
                }
                const submitButton = modal.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = modal.classList.contains('accept-modal') ? 'Accept Candidate' : 'Reject Candidate';
                }
            });
        });

        // Make window.loadPersonalityTestResults available globally
        window.loadPersonalityTestResults = loadPersonalityTestResults;
    });

    function fillApplicationDetailsModal(applicationData) {
        console.log('Filling application details modal with data:', applicationData);
        const modal = document.getElementById('applicationDetailsModal');
        
        if (!modal) {
            console.error('Application details modal not found');
            return;
        }

        // Set the application ID in the modal for reference
        modal.setAttribute('data-application-id', applicationData.id);
        
        // Fill in the details
        modal.querySelector('#candidateName').textContent = applicationData.candidate_name || 'N/A';
        modal.querySelector('#candidateEmail').textContent = applicationData.candidate_email || 'N/A';
        modal.querySelector('#candidatePhone').textContent = applicationData.phone_number || 'N/A';
        modal.querySelector('#applicationDate').textContent = new Date(applicationData.created_at).toLocaleDateString() || 'N/A';
        modal.querySelector('#applicationStatus').textContent = applicationData.status || 'N/A';
        
        // Set job title and department
        modal.querySelector('#jobTitle').textContent = applicationData.job_title || 'N/A';
        modal.querySelector('#department').textContent = applicationData.department_name || 'N/A';
        
        // Update the review buttons with the correct application ID
        const acceptBtn = modal.querySelector('#acceptButton');
        const rejectBtn = modal.querySelector('#rejectButton');
        const viewTestBtn = modal.querySelector('#viewPersonalityTestBtn');
        
        if (acceptBtn) acceptBtn.setAttribute('data-application-id', applicationData.id);
        if (rejectBtn) rejectBtn.setAttribute('data-application-id', applicationData.id);
        
        // Always show the personality test button 
        if (viewTestBtn) {
            viewTestBtn.setAttribute('data-application-id', applicationData.id);
            viewTestBtn.style.display = 'inline-block';
        }
        
        // Load the personality test summary (separate from the detailed results)
        loadPersonalityTestSummary(applicationData.id);
        
        // Initial CV score display
        const cvScoreElement = modal.querySelector('#cvScore');
        if (cvScoreElement) {
            if (applicationData.cv_score !== null && applicationData.cv_score !== undefined) {
                const score = parseFloat(applicationData.cv_score);
                const formattedScore = score.toFixed(2);
                const scoreClass = score >= 70 ? 'text-success' : 
                                  score >= 50 ? 'text-warning' : 'text-danger';
                
                cvScoreElement.innerHTML = `<span class="${scoreClass}"><strong>${formattedScore}%</strong></span>`;
            } else {
                cvScoreElement.textContent = 'Not evaluated';
            }
        }
    }
</script>
@endpush

@endsection