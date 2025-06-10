@extends('layouts.dean')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <div class="header">
            <h1>Welcome, {{ auth()->user()->name }}</h1>
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

            <!-- Job Requests Overview -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pending Job Requests</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Description</th>
                                    <th>HOD</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                @forelse($jobRequests as $request)
                            <tr>
                                <td>{{ $request->department->name }}</td>
                                <td>{{ $request->position }}</td>
                                        <td>{{ Str::limit($request->description, 100) }}</td>
                                        <td>{{ $request->hod->name }}</td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="status-badge status-{{ strtolower($request->status) }}">
                                                {{ $request->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                    <form action="{{ route('dean.approveRequest', $request->id) }}" method="POST" class="d-inline">
                                        @csrf
                                                    <button type="submit" class="btn btn-sm btn-success me-2">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal{{ $request->id }}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                            </div>

                                    <!-- Reject Modal -->
                                            <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('dean.rejectRequest', $request->id) }}" method="POST">
                                                    @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Reject Job Request</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                                    <label for="rejection_comment">Rejection Reason</label>
                                                                    <textarea class="form-control" 
                                                                              name="rejection_comment" 
                                                                              rows="3" 
                                                                              required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Reject Request</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No pending job requests found.</td>
                                    </tr>
                                @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Total Requests</h5>
                            <p class="card-text display-6">{{ $totalRequests }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Approved Requests</h5>
                            <p class="card-text display-6">{{ $approvedRequests }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Rejected Requests</h5>
                            <p class="card-text display-6">{{ $rejectedRequests }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection