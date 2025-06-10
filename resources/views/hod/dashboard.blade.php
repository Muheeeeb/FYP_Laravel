@extends('layouts.hod')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="header mb-4">
            <h1>Welcome, {{ auth()->user()->name }}</h1>
                <div class="header-actions">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(isset($error))
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endif

        @if(isset($department))
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Create Job Request</h5>
                    </div>
                <div class="card-body">
                    <form action="{{ route('hod.createJobRequest') }}" method="POST">
                        @csrf
                            <div class="form-group mb-3">
                            <label for="department">Department</label>
                                <input type="text" class="form-control @error('department_id') is-invalid @enderror" 
                                       value="{{ auth()->user()->department->name }}" readonly>
                            <input type="hidden" name="department_id" value="{{ auth()->user()->department_id }}">
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                            <div class="form-group mb-3">
                            <label for="position">Position</label>
                            <select class="form-control @error('position') is-invalid @enderror" id="position" 
                                    name="position" required onchange="checkCustomPosition(this)">
                                <option value="">Select Position</option>
                                <option value="Junior Lecturer">Junior Lecturer</option>
                                <option value="Support Office">Support Officer</option>
                                <option value="Senior Lecturer">Senior Lecturer</option>
                                <option value="Assistant Professor">Assistant Professor</option>
                                <option value="Professor">Professor</option>
                                <option value="custom">Other (Custom)</option>
                            </select>
                            
                            <input type="text" class="form-control mt-2 @error('custom_position') is-invalid @enderror" 
                                   id="custom_position" name="custom_position" 
                                   placeholder="Enter custom position" style="display: none;">
                            
                            @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                            <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                            <div class="form-group mb-3">
                            <label for="justification">Justification</label>
                            <textarea class="form-control @error('justification') is-invalid @enderror" id="justification"
                                    name="justification" rows="5" required 
                                    placeholder="Please provide justification for this job opening">{{ old('justification') }}</textarea>
                            @error('justification')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                            <div class="form-group mb-3">
                            <label for="requirements">Job Requirements</label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror" id="requirements"
                                    name="requirements" rows="5" required 
                                    placeholder="List specific job requirements, qualifications, and skills needed">{{ old('requirements') }}</textarea>
                            @error('requirements')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </form>
                </div>
            </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Recent Job Requests</h5>
                    </div>
                <div class="card-body">
                    @if($jobRequests->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Rejection Comment</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jobRequests as $request)
                            <tr>
                                <td>{{ $request->department->name }}</td>
                                <td>{{ $request->position }}</td>
                                <td>{{ Str::limit($request->description, 100) }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($request->status) }}">
                                        {{ $request->status }}
                                    </span>
                                </td>
                                <td>{{ $request->rejection_comment ?? 'N/A' }}</td>
                                <td>{{ $request->created_at->format('M d, Y') }}</td>
                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            onclick="openEditModal({{ $request->id }}, '{{ $request->position }}', '{{ addslashes($request->description) }}', '{{ addslashes($request->justification) }}', '{{ addslashes($request->requirements) }}')">
                                                            <i class="fas fa-edit"></i>
                                        </button>
                                                        <form action="{{ route('deleteJobRequest', $request->id) }}" 
                                                              method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this request?')">
                                            @csrf
                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                            </div>
                    @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                No job requests found.
                            </div>
                    @endif
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Please contact administrator to assign a department.
                </div>
            @endif
        </div>
                </div>
            </div>

            <!-- Edit Job Request Modal -->
<div class="modal fade" id="editJobRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editJobRequestForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Job Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                    <div class="form-group mb-3">
                                    <label for="editPosition">Position</label>
                                    <select class="form-control" id="editPosition" name="position" required onchange="checkCustomPosition(this)">
                                        <option value="">Select Position</option>
                                        <option value="Junior Lecturer">Junior Lecturer</option>
                            <option value="Support Office">Support Officer</option>
                                        <option value="Senior Lecturer">Senior Lecturer</option>
                                        <option value="Assistant Professor">Assistant Professor</option>
                                        <option value="Professor">Professor</option>
                                        <option value="custom">Other (Custom)</option>
                                    </select>
                                    <input type="text" class="form-control mt-2" id="editCustomPosition" 
                                           name="custom_position" placeholder="Enter custom position" 
                                           style="display: none;">
                                </div>
                    <div class="form-group mb-3">
                                    <label for="editDescription">Description</label>
                                    <textarea class="form-control" id="editDescription" name="description" 
                                              rows="3" required></textarea>
                                </div>
                    <div class="form-group mb-3">
                                    <label for="editJustification">Justification</label>
                                    <textarea class="form-control" id="editJustification" name="justification" 
                                              rows="5" required></textarea>
                                </div>
                    <div class="form-group mb-3">
                                    <label for="editRequirements">Job Requirements</label>
                                    <textarea class="form-control" id="editRequirements" name="requirements" 
                                              rows="5" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

<style>
    :root {
        --primary-color: #2563eb;
        --secondary-color: #1e40af;
        --background-color: #f1f5f9;
        --text-color: #334155;
        --white: #ffffff;
        --card-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header h1 {
        font-size: 1.5rem;
        color: var(--text-color);
        margin: 0;
    }

    .card {
        background: var(--white);
        border-radius: 0.5rem;
        box-shadow: var(--card-shadow);
    }

    .card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .table td {
        vertical-align: middle;
        padding: 0.75rem;
    }

    .table th {
        font-weight: 600;
        background-color: #f8fafc;
        padding: 0.75rem;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-approved {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-rejected {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .status-posted {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 0.5rem;
        border: 1px solid transparent;
    }

    .form-control {
        border-radius: 0.375rem;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.75rem;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
    }

    .modal-content {
        border-radius: 0.5rem;
        box-shadow: var(--card-shadow);
    }
</style>

@push('scripts')
<script>
        function checkCustomPosition(selectElement) {
            const customInput = selectElement.id === 'position' ? 
                               document.getElementById('custom_position') : 
                               document.getElementById('editCustomPosition');
            
            if (selectElement.value === 'custom') {
                customInput.style.display = 'block';
                customInput.required = true;
            } else {
                customInput.style.display = 'none';
                customInput.required = false;
                customInput.value = '';
            }
        }

        function openEditModal(id, position, description, justification, requirements) {
            const form = document.getElementById('editJobRequestForm');
            form.action = `/hod/job-requests/${id}`;
            
            const positionSelect = document.getElementById('editPosition');
            const customPositionInput = document.getElementById('editCustomPosition');
            
            const defaultPositions = [
                'Junior Lecturer',
                'Support Office',
                'Senior Lecturer',
                'Assistant Professor',
                'Professor'
            ];
            
            if (defaultPositions.includes(position)) {
                positionSelect.value = position;
                customPositionInput.style.display = 'none';
                customPositionInput.value = '';
                customPositionInput.required = false;
            } else {
                positionSelect.value = 'custom';
                customPositionInput.style.display = 'block';
                customPositionInput.value = position;
                customPositionInput.required = true;
            }
            
            document.getElementById('editDescription').value = description;
            document.getElementById('editJustification').value = justification;
            document.getElementById('editRequirements').value = requirements;

            const modal = new bootstrap.Modal(document.getElementById('editJobRequestModal'));
            modal.show();
        }

        document.getElementById('editJobRequestForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const positionSelect = document.getElementById('editPosition');
            const customPositionInput = document.getElementById('editCustomPosition');
            
            if (positionSelect.value === 'custom' && !customPositionInput.value.trim()) {
                alert('Please enter a custom position');
                return;
            }
            
            this.submit();
        });
    </script>
@endpush

@endsection