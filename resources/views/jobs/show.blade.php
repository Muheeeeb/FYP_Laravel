@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $job->position ?? 'Job Details' }}</h3>
                </div>
                <div class="card-body">
                    <div class="job-details">
                        <p><strong>Department:</strong> {{ optional($job->department)->name ?? 'N/A' }}</p>
                        <p><strong>Position:</strong> {{ $job->position ?? 'N/A' }}</p>
                        <p><strong>Status:</strong> {{ $job->status ?? 'Pending' }}</p>
                        <div class="description">
                            <h4>Job Description</h4>
                            <p>{{ $job->description ?? 'No description available' }}</p>
                        </div>
                        @if($job->requirements)
                        <div class="requirements">
                            <h4>Requirements</h4>
                            <p>{{ $job->requirements }}</p>
                        </div>
                        @endif
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('jobs.apply', $job->id) }}" class="btn btn-success">Apply Now</a>
                        <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Back to Listings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection