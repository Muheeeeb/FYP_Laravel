@extends('layouts.hr')

@section('title', 'Manage Jobs')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Job Postings</h5>
                    <a href="{{ route('hr.job-posting') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Post New Job
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

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
                                @forelse($jobs as $job)
                                    <tr>
                                        <td>{{ $job->title }}</td>
                                        <td>{{ $job->jobRequest?->department?->name ?? 'N/A' }}</td>
                                        <td>{{ $job->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('hr.applications.job', $job->id) }}" class="text-primary">
                                                {{ $job->applications->count() }} applications
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $job->status === 'Active' ? 'success' : 'secondary' }}">
                                                {{ $job->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('hr.applications.job', $job->id) }}" 
                                                   class="btn btn-sm btn-info me-2" 
                                                   title="View Applications">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($job->status === 'Active')
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#closeJobModal{{ $job->id }}"
                                                            title="Close Job">
                                                        <i class="fas fa-times"></i>
                                                    </button>

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
                                                @endif
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
        </div>
    </div>
</div>
@endsection