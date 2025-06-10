@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <div class="card-header" style="background: white; border-bottom: 1px solid #eee; padding: 20px;">
                    <h3 style="color: #006dd7; margin: 0; font-size: 24px;">{{ $job->position }}</h3>
                </div>
                <div class="card-body" style="padding: 25px;">
                    <div class="job-details">
                        <p style="color: #666; margin-bottom: 10px;">
                            <strong>Department:</strong> {{ $job->department->name ?? 'Department Not Specified' }}
                        </p>
                        <p style="color: #666; margin-bottom: 10px;">
                            <strong>Position:</strong> {{ $job->position }}
                        </p>
                        @if($job->lab_attendant_required)
                            <p style="color: #666; margin-bottom: 10px;">
                                Lab attendant required
                            </p>
                        @endif
                        <p style="color: #666; margin-bottom: 10px;">
                            <strong>Posted:</strong> {{ $job->created_at->format('M d, Y') }}
                        </p>

                        @if($job->description)
                            <div class="description" style="margin-top: 20px;">
                                <h4 style="color: #006dd7; margin-bottom: 10px;">Job Description</h4>
                                <p style="color: #666;">{{ $job->description }}</p>
                            </div>
                        @endif

                        @if($job->requirements)
                            <div class="requirements" style="margin-top: 20px;">
                                <h4 style="color: #006dd7; margin-bottom: 10px;">Requirements</h4>
                                <p style="color: #666;">{{ $job->requirements }}</p>
                            </div>
                        @endif
                    </div>

                    <div style="text-align: center; margin-top: 30px;">
                        <a href="{{ route('jobs.apply', $job->id) }}" class="btn btn-primary">
                            Apply Now
                        </a>
                        <a href="{{ route('jobs.index') }}" 
                           style="display: inline-block; padding: 12px 30px; background: #666; color: white; text-decoration: none; border-radius: 5px; font-weight: 500;">
                            Back to Listings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection