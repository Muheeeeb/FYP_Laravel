@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Job Requests</h1>
        <a href="{{ route('admin.job-requests.create') }}" class="btn btn-primary">Create New Request</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Manage Job Requests</h6>
            <div class="search-box">
                <form action="{{ route('admin.job-requests.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control" placeholder="Search by department, position..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary ml-2">Search</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="jobRequestsTable">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>HOD</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobRequests as $request)
                        <tr>
                            <td><input type="checkbox" name="job_requests[]" value="{{ $request->id }}" class="request-checkbox"></td>
                            <td>{{ $request->department->name }}</td>
                            <td>{{ $request->position }}</td>
                            <td>{{ $request->hod->name }}</td>
                            <td>
                                <span class="badge badge-{{ $request->status == 'approved_by_dean' ? 'success' : ($request->status == 'pending' ? 'warning' : 'info') }} text-dark">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                            </td>
                            <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.job-requests.show', $request->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('admin.job-requests.edit', $request->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('admin.job-requests.destroy', $request->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        Showing {{ $jobRequests->firstItem() }} to {{ $jobRequests->lastItem() }} of {{ $jobRequests->total() }} results
                    </div>
                    <div>
                        {{ $jobRequests->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.request-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
@endpush
@endsection