@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Job Request</h1>
        <a href="{{ route('admin.job-requests.index') }}" class="btn btn-secondary">Back to List</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.job-requests.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="department_id">Department</label>
                    <select name="department_id" id="department_id" class="form-control @error('department_id') is-invalid @enderror">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
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
                            <option value="{{ $hod->id }}" {{ old('hod_id') == $hod->id ? 'selected' : '' }}>
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
                    <input type="text" name="position" id="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position') }}">
                    @error('position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved_by_dean" {{ old('status') == 'approved_by_dean' ? 'selected' : '' }}>Approved by Dean</option>
                        <option value="posted_by_hr" {{ old('status') == 'posted_by_hr' ? 'selected' : '' }}>Posted by HR</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Create Job Request</button>
            </form>
        </div>
    </div>
</div>
@endsection