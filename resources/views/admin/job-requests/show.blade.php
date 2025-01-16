@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">View Job Request</h1>
        <div>
            <a href="{{ route('admin.job-requests.edit', $jobRequest->id) }}" class="btn btn-primary">Edit Request</a>
            <a href="{{ route('admin.job-requests.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Job Request Details Card -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Job Request Details</h6>
                    <span class="badge badge-{{ $jobRequest->status == 'approved_by_dean' ? 'success' : ($jobRequest->status == 'pending' ? 'warning' : 'info') }} px-3 py-2">
                        {{ ucfirst(str_replace('_', ' ', $jobRequest->status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Department:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $jobRequest->department->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>HOD:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $jobRequest->hod->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Position:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $jobRequest->position }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Description:</strong>
                        </div>
                        <div class="col-md-8">
                            {!! nl2br(e($jobRequest->description)) !!}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Created At:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $jobRequest->created_at->format('Y-m-d H:i:s') }}
                        </div>
                    </div>

                    @if($jobRequest->approved_by_dean_at)
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Approved by Dean:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ \Carbon\Carbon::parse($jobRequest->approved_by_dean_at)->format('Y-m-d H:i:s') }}
                        </div>
                    </div>
                    @endif

                    @if($jobRequest->posted_by_hr_at)
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Posted by HR:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ \Carbon\Carbon::parse($jobRequest->posted_by_hr_at)->format('Y-m-d H:i:s') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    @if($jobRequest->status !== 'approved_by_dean')
                    <form action="{{ route('admin.job-requests.approve-dean', $jobRequest->id) }}" 
                          method="POST" 
                          class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-check mr-1"></i> Approve by Dean
                        </button>
                    </form>
                    @endif

                    @if($jobRequest->status === 'approved_by_dean' && !$jobRequest->posted_by_hr_at)
                    <form action="{{ route('admin.job-requests.post-hr', $jobRequest->id) }}" 
                          method="POST" 
                          class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fas fa-paper-plane mr-1"></i> Post to HR
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('admin.job-requests.destroy', $jobRequest->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this job request?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash mr-1"></i> Delete Request
                        </button>
                    </form>
                </div>
            </div>

            @if($jobRequest->jobPostings->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Related Job Postings</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($jobRequest->jobPostings as $posting)
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $posting->title }}</h6>
                                <small>{{ $posting->created_at->diffForHumans() }}</small>
                            </div>
                            <small class="text-muted">Status: {{ ucfirst($posting->status) }}</small>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge {
        font-size: 0.85rem;
    }
    .list-group-item:hover {
        background-color: #f8f9fc;
    }
</style>
@endpush
@endsection