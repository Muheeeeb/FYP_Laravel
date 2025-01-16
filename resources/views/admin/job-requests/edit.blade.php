@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Job Request</h1>
        <a href="{{ route('admin.job-requests.index') }}" class="btn btn-secondary">Back to List</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.job-requests.update', $jobRequest->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="department_id">Department</label>
                    <select name="department_id" id="department_id" class="form-control @error('department_id') is-invalid @enderror">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" 
                                {{ (old('department_id', $jobRequest->department_id) == $department->id) ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="hod_id">HOD</label>
                    <select name="hod_id" id="hod_id" class="form-control @error('hod_id') is-invalid @enderror">
                        <option value="">Select HOD</option>
                        @foreach($hods as $hod)
                            <option value="{{ $hod->id }}" 
                                {{ (old('hod_id', $jobRequest->hod_id) == $hod->id) ? 'selected' : '' }}>
                                {{ $hod->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('hod_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="position">Position</label>
                    <input type="text" 
                           name="position" 
                           id="position" 
                           class="form-control @error('position') is-invalid @enderror" 
                           value="{{ old('position', $jobRequest->position) }}">
                    @error('position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" 
                              id="description" 
                              rows="4" 
                              class="form-control @error('description') is-invalid @enderror"
                    >{{ old('description', $jobRequest->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="pending" 
                            {{ old('status', $jobRequest->status) == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="approved_by_dean" 
                            {{ old('status', $jobRequest->status) == 'approved_by_dean' ? 'selected' : '' }}>
                            Approved by Dean
                        </option>
                        <option value="posted_by_hr" 
                            {{ old('status', $jobRequest->status) == 'posted_by_hr' ? 'selected' : '' }}>
                            Posted by HR
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <button type="submit" class="btn btn-primary">Update Job Request</button>
                    </div>
                    
                    @if($jobRequest->status !== 'approved_by_dean')
                    <div class="col text-right">
                        <form action="{{ route('admin.job-requests.approve-dean', $jobRequest->id) }}" 
                              method="POST" 
                              class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                Approve by Dean
                            </button>
                        </form>
                    </div>
                    @endif

                    @if($jobRequest->status === 'approved_by_dean' && !$jobRequest->posted_by_hr_at)
                    <div class="col text-right">
                        <form action="{{ route('admin.job-requests.post-hr', $jobRequest->id) }}" 
                              method="POST" 
                              class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-info">
                                Post to HR
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </form>

            <div class="mt-4">
                <h5>Request Timeline</h5>
                <ul class="timeline">
                    <li>
                        <strong>Created:</strong> 
                        {{ $jobRequest->created_at->format('Y-m-d H:i:s') }}
                    </li>
                    @if($jobRequest->approved_by_dean_at)
                    <li>
                        <strong>Approved by Dean:</strong> 
                        {{ \Carbon\Carbon::parse($jobRequest->approved_by_dean_at)->format('Y-m-d H:i:s') }}
                    </li>
                    @endif
                    @if($jobRequest->posted_by_hr_at)
                    <li>
                        <strong>Posted by HR:</strong> 
                        {{ \Carbon\Carbon::parse($jobRequest->posted_by_hr_at)->format('Y-m-d H:i:s') }}
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .timeline {
        list-style-type: none;
        padding: 0;
        position: relative;
    }
    .timeline li {
        margin: 10px 0;
        padding-left: 20px;
        border-left: 2px solid #e3e6f0;
    }
    .timeline li:last-child {
        border-left: none;
    }
</style>
@endpush
@endsection