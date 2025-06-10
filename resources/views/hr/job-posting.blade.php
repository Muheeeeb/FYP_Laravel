@extends('layouts.hr')

@section('title', 'Job Postings')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Job Postings</h1>
        <a href="{{ route('hr.manage.jobs') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Manage Job Postings
        </a>
        </div>

        @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

    <!-- Job Postings Card -->
    <div class="card shadow">
            <div class="card-body">
                @if($jobPostings->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-3"></i>
                    <p class="text-gray-500 mb-0">No job postings found</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Requirements</th>
                                <th>Posted At</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jobPostings->sortByDesc('created_at') as $posting)
                            <tr>
                                <td class="fw-medium">{{ $posting->title }}</td>
                                <td>{{ Str::limit($posting->description, 100) }}</td>
                                <td>{{ Str::limit($posting->requirements, 100) }}</td>
                                <td>{{ $posting->created_at ? $posting->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('hr.applications.job', $posting->id) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View Applications
                                        </a>
                                        <form action="{{ route('hr.delete.job-posting', $posting->id) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this job posting?')">
                                                <i class="fas fa-trash-alt me-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
</div>
@endsection