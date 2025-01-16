<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOD Dashboard - SZABIST Hiring Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --background-color: #f1f5f9;
            --sidebar-color: #1e293b;
            --text-color: #334155;
            --white: #ffffff;
            --card-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: var(--sidebar-color);
            padding: 1.5rem;
            position: fixed;
            left: 0;
            top: 0;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            color: var(--white);
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo img {
            width: 40px;
            margin-right: 0.5rem;
        }

        .nav-links {
            list-style: none;
            padding: 0;
        }

        .nav-links li {
            margin-bottom: 0.5rem;
        }

        .nav-links a {
            color: #94a3b8;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--white);
        }

        .nav-links i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
            width: calc(100% - 250px);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 1.5rem;
            color: var(--text-color);
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.5rem;
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .card {
            background: var(--white);
            border-radius: 0.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }

        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .invalid-feedback {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            font-weight: 600;
            background-color: #f8fafc;
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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal.show {
            display: block;
        }

        .modal-dialog {
            margin: 1.75rem auto;
            max-width: 500px;
        }

        .modal-content {
            background-color: var(--white);
            border-radius: 0.5rem;
            box-shadow: var(--card-shadow);
        }

        .modal-header {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 1rem;
        }

        .modal-footer {
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-logo" style="
            text-align: center;
            padding: 15px 0;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
            <span style="
                font-size: 24px; 
                font-weight: 500; 
                color: #e2e8f0;
                font-family: 'Poppins', sans-serif;
                display: inline-block;">
                HOD 
            </span>
        </div>
        <ul class="nav-links">
            <li><a href="{{ route('hod.dashboard') }}" class="active"><i class="fas fa-home"></i>Dashboard</a></li>
            <li><a href="{{ route('hod.candidates') }}"><i class="fas fa-user-tie"></i>Candidates</a></li>
            <li><a href="{{ route('hod.analytics') }}"><i class="fas fa-chart-bar"></i>Analytics</a></li>
            <li><a href="{{ route('hod.settings') }}"><i class="fas fa-cog"></i>Settings</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Welcome, {{ auth()->user()->name }}</h1>
            <div class="user-info">
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
            <div class="card">
                <div class="card-header">Create Job Request</div>
                <div class="card-body">
                    <form action="{{ route('hod.createJobRequest') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="department">Department</label>
                            <input type="text" 
                                   class="form-control @error('department_id') is-invalid @enderror" 
                                   value="{{ auth()->user()->department->name }}" 
                                   readonly>
                            <input type="hidden" name="department_id" value="{{ auth()->user()->department_id }}">
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="position">Position</label>
                            <select class="form-control @error('position') is-invalid @enderror" id="position" 
                                    name="position" required onchange="checkCustomPosition(this)">
                                <option value="">Select Position</option>
                                <option value="Junior Lecturer">Junior Lecturer</option>
                                <option value="Support Office">Support Office</option>
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

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="justification">Justification</label>
                            <textarea class="form-control @error('justification') is-invalid @enderror" id="justification"
                                name="justification" rows="3" required placeholder="Please provide justification for this job opening">{{ old('justification') }}</textarea>
                            @error('justification')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Recent Job Requests</div>
                <div class="card-body">
                    @if($jobRequests->count() > 0)
                    <table>
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
                                    <div style="display: inline-flex; align-items: center;">
                                        <button type="button"
                                            onclick="openEditModal({{ $request->id }}, '{{ $request->position }}', '{{ addslashes($request->description) }}')"
                                            style="background: none; border: none; padding: 5px; cursor: pointer;">
                                            <i class="fas fa-edit" style="color: #ffc107;"></i>
                                        </button>
                                        <form action="{{ route('hod.deleteJobRequest', $request->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this request?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="background: none; border: none; padding: 5px; cursor: pointer;">
                                                <i class="fas fa-trash" style="color: #dc3545;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-center">No job requests found.</p>
                    @endif
                </div>
            </div>

            <!-- Edit Job Request Modal -->
            <div id="editJobRequestModal" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form id="editJobRequestForm" action="" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Job Request</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    style="border: none; background: transparent; font-size: 1.5rem; padding: 0; cursor: pointer;">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="editPosition">Position</label>
                                    <select class="form-control" id="editPosition" name="position" required 
                                            onchange="checkCustomPosition(this)">
                                        <option value="">Select Position</option>
                                        <option value="Junior Lecturer">Junior Lecturer</option>
                                        <option value="Support Office">Support Office</option>
                                        <option value="Senior Lecturer">Senior Lecturer</option>
                                        <option value="Assistant Professor">Assistant Professor</option>
                                        <option value="Professor">Professor</option>
                                        <option value="custom">Other (Custom)</option>
                                    </select>
                                    <input type="text" class="form-control mt-2" id="editCustomPosition" 
                                           name="custom_position" placeholder="Enter custom position" 
                                           style="display: none;">
                                </div>
                                <div class="form-group">
                                    <label for="editDescription">Description</label>
                                    <textarea class="form-control" id="editDescription" name="description" 
                                              rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                Please contact administrator to assign a department.
            </div>
        @endif
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        window.onload = function() {
            if (performance.navigation.type === 2) {
                fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                }).then(() => {
                    window.location.href = '/login';
                });
            }
        };

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

        function openEditModal(id, position, description) {
            const form = document.getElementById('editJobRequestForm');
            form.action = `/hod/job-requests/${id}`;
            
            const positionSelect = document.getElementById('editPosition');
            const customPositionInput = document.getElementById('editCustomPosition');
            
            const defaultPositions = ['Junior Lecturer', 'Support Office', 'Senior Lecturer', 
                                    'Assistant Professor', 'Professor'];
            
            if (defaultPositions.includes(position)) {
                positionSelect.value = position;
                customPositionInput.style.display = 'none';
                customPositionInput.required = false;
            } else {
                positionSelect.value = 'custom';
                customPositionInput.style.display = 'block';
                customPositionInput.required = true;
                customPositionInput.value = position;
            }
            
            document.getElementById('editDescription').value = description;
            $('#editJobRequestModal').modal('show');
        }
    </script>
</body>
</html>