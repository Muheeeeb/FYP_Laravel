@extends('layouts.admin')

@section('title', 'Add New User')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Add New User</h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required>
                                <small class="text-muted">
                                    Password must contain:
                                   
                                </small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required>
                                <div id="password-match-feedback" class="invalid-feedback" style="display: none;">
                                    Passwords do not match
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        required>
                                    <option value="">Select Role</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="hod" {{ old('role') == 'hod' ? 'selected' : '' }}>HOD</option>
                                    <option value="dean" {{ old('role') == 'dean' ? 'selected' : '' }}>Dean</option>
                                    <option value="hr" {{ old('role') == 'hr' ? 'selected' : '' }}>HR</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3" id="departmentSection" style="display: none;">
                                <label for="department_id" class="form-label">Department</label>
                                <select class="form-select @error('department_id') is-invalid @enderror" 
                                        id="department_id" 
                                        name="department_id">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" 
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}
                                            {{ $department->hod_id ? 'disabled' : '' }}>
                                            {{ $department->name }}
                                            {{ $department->hod_id ? ' (Already has HOD)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to Users
                                    </a>
                                    <div>
                                        <button type="reset" class="btn btn-outline-secondary me-2">
                                            <i class="fas fa-undo"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-user-plus"></i> Create User
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        margin-bottom: 1.5rem;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .form-label {
        font-weight: 500;
    }
    .btn i {
        margin-right: 0.5rem;
    }
    .invalid-feedback {
        font-size: 80%;
    }
    .text-muted ul {
        font-size: 0.875rem;
        padding-left: 1.2rem;
        margin-top: 0.5rem;
    }
    .password-requirements {
        margin-top: 0.5rem;
    }
    .text-danger {
        color: #dc3545;
    }
    .password-requirements-feedback {
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
    button[type="submit"]:disabled {
        cursor: not-allowed;
        opacity: 0.65;
    }
    #password-requirements-feedback ul {
        list-style-type: none;
        padding-left: 0;
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }
    #password-requirements-feedback li {
        color: #dc3545;
        margin-bottom: 0.25rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const departmentSection = document.getElementById('departmentSection');

        function toggleDepartmentSection() {
            if (roleSelect.value === 'hod') {
                departmentSection.style.display = 'block';
                document.getElementById('department_id').required = true;
            } else {
                departmentSection.style.display = 'none';
                document.getElementById('department_id').required = false;
                document.getElementById('department_id').value = '';
            }
        }

        roleSelect.addEventListener('change', toggleDepartmentSection);
        toggleDepartmentSection(); // Run on page load

        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const form = document.querySelector('form');
        const submitButton = document.querySelector('button[type="submit"]');

        // Password validation requirements
        const passwordRequirements = {
            minLength: 8,
            patterns: {
                uppercase: /[A-Z]/,
                lowercase: /[a-z]/,
                number: /[0-9]/,
                special: /[!@#$%^&*]/
            }
        };

        function validatePassword(password) {
            const checks = {
                length: password.length >= passwordRequirements.minLength,
                uppercase: passwordRequirements.patterns.uppercase.test(password),
                lowercase: passwordRequirements.patterns.lowercase.test(password),
                number: passwordRequirements.patterns.number.test(password),
                special: passwordRequirements.patterns.special.test(password)
            };

            // Create feedback message
            let feedbackHtml = '<ul class="mb-0 text-danger">';
            if (!checks.length) feedbackHtml += '<li>Must be at least 8 characters</li>';
            if (!checks.uppercase) feedbackHtml += '<li>Must contain an uppercase letter</li>';
            if (!checks.lowercase) feedbackHtml += '<li>Must contain a lowercase letter</li>';
            if (!checks.number) feedbackHtml += '<li>Must contain a number</li>';
            if (!checks.special) feedbackHtml += '<li>Must contain a special character (!@#$%^&*)</li>';
            feedbackHtml += '</ul>';

            // Update feedback display
            const feedbackElement = document.getElementById('password-requirements-feedback');
            feedbackElement.innerHTML = feedbackHtml;

            return Object.values(checks).every(check => check === true);
        }

        // Add password requirements feedback div after password input
        const requirementsFeedback = document.createElement('div');
        requirementsFeedback.id = 'password-requirements-feedback';
        requirementsFeedback.className = 'mt-2';
        passwordInput.parentNode.appendChild(requirementsFeedback);

        function updateSubmitButton() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const isPasswordValid = validatePassword(password);
            const doPasswordsMatch = password === confirmPassword;

            // Enable submit button only if all conditions are met
            submitButton.disabled = !(isPasswordValid && doPasswordsMatch);

            // Visual feedback
            if (!isPasswordValid) {
                passwordInput.classList.add('is-invalid');
            } else {
                passwordInput.classList.remove('is-invalid');
            }

            if (confirmPassword && !doPasswordsMatch) {
                confirmPasswordInput.classList.add('is-invalid');
                document.getElementById('password-match-feedback').style.display = 'block';
            } else {
                confirmPasswordInput.classList.remove('is-invalid');
                document.getElementById('password-match-feedback').style.display = 'none';
            }
        }

        // Add event listeners
        passwordInput.addEventListener('input', updateSubmitButton);
        confirmPasswordInput.addEventListener('input', updateSubmitButton);

        // Prevent form submission if validation fails
        form.addEventListener('submit', function(e) {
            const password = passwordInput.value;
            if (!validatePassword(password)) {
                e.preventDefault();
                alert('Please ensure your password meets all requirements.');
                return false;
            }
            if (password !== confirmPasswordInput.value) {
                e.preventDefault();
                alert('Passwords do not match.');
                return false;
            }
        });

        // Initial validation check
        updateSubmitButton();
    });
</script>
@endpush