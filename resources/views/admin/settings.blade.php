@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">System Settings</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- General Settings -->
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="text-primary">General Settings</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label>Site Name</label>
                    <input type="text" 
                           class="form-control" 
                           name="site_name" 
                           value="{{ old('site_name', config('app.name')) }}">
                </div>

                <div class="form-group">
                    <label>Admin Email</label>
                    <input type="email" 
                           class="form-control" 
                           name="admin_email" 
                           value="{{ old('admin_email', config('mail.from.address')) }}">
                </div>

                <button type="submit" class="btn btn-primary">Save General Settings</button>
            </form>
        </div>
    </div>

    <!-- Email Settings -->
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="text-primary">Email Settings</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update-email') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Mail Driver</label>
                    <input type="text" class="form-control" name="mail_driver" value="smtp" readonly>
                </div>

                <div class="form-group">
                    <label>SMTP Host</label>
                    <input type="text" 
                           class="form-control" 
                           name="mail_host" 
                           value="smtp.gmail.com">
                </div>

                <div class="form-group">
                    <label>SMTP Port</label>
                    <input type="text" 
                           class="form-control" 
                           name="mail_port" 
                           value="587">
                </div>

                <div class="form-group">
                    <label>SMTP Username</label>
                    <input type="email" 
                           class="form-control" 
                           name="mail_username" 
                           value="{{ config('mail.mailers.smtp.username') }}" 
                           placeholder="your-gmail@gmail.com">
                </div>

                <div class="form-group">
                    <label>SMTP Password</label>
                    <input type="password" 
                           class="form-control" 
                           name="mail_password" 
                           placeholder="Your Gmail App Password">
                    <small class="text-muted">Use App Password from Google Account settings</small>
                </div>

                <div class="form-group">
                    <label>Encryption</label>
                    <input type="text" 
                           class="form-control" 
                           name="mail_encryption" 
                           value="tls" 
                           readonly>
                </div>

                <button type="submit" class="btn btn-primary">Save Email Settings</button>
            </form>
        </div>
    </div>

    <!-- Notification Settings -->
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="text-primary">Notification Settings</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update-notifications') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" 
                               class="custom-control-input" 
                               id="email_notifications" 
                               name="email_notifications" 
                               {{ config('notifications.email', true) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="email_notifications">
                            Email Notifications
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" 
                               class="custom-control-input" 
                               id="system_notifications" 
                               name="system_notifications" 
                               {{ config('notifications.system', true) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="system_notifications">
                            System Notifications
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save Notification Settings</button>
            </form>
        </div>
    </div>

    <!-- Job Request Settings -->
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="text-primary">Job Request Settings</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update-job-requests') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Approval Workflow</label>
                    <select class="form-control" name="approval_workflow">
                        <option value="simple" {{ config('job-requests.approval_workflow') == 'simple' ? 'selected' : '' }}>
                            Simple (HOD → HR)
                        </option>
                        <option value="advanced" {{ config('job-requests.approval_workflow') == 'advanced' ? 'selected' : '' }}>
                            Advanced (HOD → Dean → HR)
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Auto Post Delay (Hours)</label>
                    <input type="number" 
                           class="form-control" 
                           name="auto_post_delay" 
                           value="{{ config('job-requests.auto_post_delay', 24) }}" 
                           min="1">
                </div>

                <button type="submit" class="btn btn-primary">Save Job Request Settings</button>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        margin-bottom: 1.5rem;
    }
    .card-header {
        background-color: white;
        border-bottom: 1px solid #e3e6f0;
    }
    .card-header h2 {
        font-size: 1.1rem;
        margin: 0;
        color: #4e73df;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .form-group label {
        font-weight: 500;
    }
    .text-muted {
        font-size: 0.875rem;
    }
</style>
@endpush
@endsection