<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HodController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\DeanController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\AdminJobRequestController;
use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home route
Route::get('/', function () {
    return view('home');
})->name('home');

// Public Job Listing Routes
Route::get('/jobs', [JobListingController::class, 'index'])->name('jobs.listings');
Route::get('/jobs/{id}', [JobListingController::class, 'show'])->name('jobs.show');
Route::get('/jobs/{id}/apply', [JobListingController::class, 'apply'])->name('jobs.apply');
Route::post('/jobs/{id}/apply', [JobListingController::class, 'submitApplication'])->name('jobs.submit-application');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Guest routes
Route::middleware(['guest'])->group(function () {
    // Login
    Route::get('/login', function () { 
        return view('login'); 
    })->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Registration
    Route::get('/register', function () { 
        return view('register'); 
    })->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Password Reset
    Route::get('/forgot-password', function () {
        return view('forgot-password');
    })->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
        ->name('password.email');
    Route::get('/reset-password/{token}', function ($token) {
        return view('reset-password', ['token' => $token]);
    })->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
        ->name('password.update');
});

// Logout
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('prevent.back.history');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes for admin
    Route::middleware(['guest'])->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // Protected admin routes
    Route::middleware(['auth', 'admin', 'prevent.back.history'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // User Management
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggleStatus');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])
            ->name('users.resetPassword');
        
        // Role Management
        Route::resource('roles', RoleController::class);
        
        // Department Management
        Route::resource('departments', DepartmentController::class);
        
        // Job Request Management
        Route::prefix('job-requests')->name('job-requests.')->group(function () {
            Route::get('/', [AdminJobRequestController::class, 'index'])->name('index');
            Route::get('/create', [AdminJobRequestController::class, 'create'])->name('create');
            Route::post('/', [AdminJobRequestController::class, 'store'])->name('store');
            Route::get('/{jobRequest}', [AdminJobRequestController::class, 'show'])->name('show');
            Route::get('/{jobRequest}/edit', [AdminJobRequestController::class, 'edit'])->name('edit');
            Route::put('/{jobRequest}', [AdminJobRequestController::class, 'update'])->name('update');
            Route::delete('/{jobRequest}', [AdminJobRequestController::class, 'destroy'])->name('destroy');
            Route::post('/{jobRequest}/approve-dean', [AdminJobRequestController::class, 'approveDean'])->name('approve-dean');
            Route::post('/{jobRequest}/post-hr', [AdminJobRequestController::class, 'postHR'])->name('post-hr');
            Route::post('/bulk-action', [AdminJobRequestController::class, 'bulkAction'])->name('bulk-action');
        });

        // Analytics & Reports
        Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [AdminController::class, 'reports'])->name('index');
            Route::post('/generate', [AdminController::class, 'generateReport'])->name('generate');
            Route::get('/download/{report}', [AdminController::class, 'downloadReport'])->name('download');
        });

        // Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::put('/settings/general', [AdminController::class, 'updateSettings'])->name('settings.update');
        Route::put('/settings/email', [AdminController::class, 'updateEmailSettings'])->name('settings.update-email');
        Route::put('/settings/notifications', [AdminController::class, 'updateNotificationSettings'])->name('settings.update-notifications');
        Route::put('/settings/job-requests', [AdminController::class, 'updateJobRequestSettings'])->name('settings.update-job-requests');

        // Profile
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');

        // Logout
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});

/*
|--------------------------------------------------------------------------
| Role-Based Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'prevent.back.history'])->group(function () {
    
    // HOD Routes
    Route::middleware(['auth', 'check.role:hod'])->prefix('hod')->name('hod.')->group(function () {
        Route::get('/dashboard', [HodController::class, 'dashboard'])->name('dashboard');
        Route::get('/job-request/create', [HodController::class, 'createJobRequestForm'])->name('createJobRequestForm');
        Route::post('/job-request', [HodController::class, 'createJobRequest'])->name('createJobRequest');
        Route::get('/job-request/{id}/edit', [HodController::class, 'editJobRequest'])->name('editJobRequest');
        Route::put('/job-requests/{id}', [HodController::class, 'updateJobRequest'])->name('updateJobRequest');
        Route::delete('/job-request/{id}', [HodController::class, 'deleteJobRequest'])->name('deleteJobRequest');
        Route::get('/candidates', [HodController::class, 'candidates'])->name('candidates');
        Route::get('/analytics', [HodController::class, 'analytics'])->name('analytics');
        Route::get('/settings', [HodController::class, 'settings'])->name('settings');
        Route::put('/settings', [HodController::class, 'updateSettings'])->name('updateSettings');
        Route::get('/requests/all', [HodController::class, 'allRequests'])->name('requests.all');
        Route::get('/requests/pending', [HodController::class, 'pendingRequests'])->name('requests.pending');
        Route::get('/requests/approved', [HodController::class, 'approvedRequests'])->name('requests.approved');
        Route::get('/requests/rejected', [HodController::class, 'rejectedRequests'])->name('requests.rejected');
        Route::get('/requests/posted', [HodController::class, 'postedRequests'])->name('requests.posted');
        Route::get('/requests/{request}', [HodController::class, 'show'])->name('requests.show');
    });

    // Dean Routes
    Route::middleware(['check.role:dean'])->prefix('dean')->name('dean.')->group(function () {
        Route::get('/dashboard', [DeanController::class, 'dashboard'])->name('dashboard');
        Route::get('/analytics', [DeanController::class, 'analytics'])->name('analytics');
        Route::post('/approve-request/{id}', [DeanController::class, 'approveRequest'])->name('approveRequest');
        Route::post('/reject-request/{id}', [DeanController::class, 'rejectRequest'])->name('rejectRequest');
    });

    // HR Routes
    Route::middleware(['check.role:hr'])->prefix('hr')->name('hr.')->group(function () {
        Route::get('/dashboard', [HrController::class, 'dashboard'])->name('dashboard');
        Route::get('/analytics', [HrController::class, 'analytics'])->name('analytics');
        Route::post('/post-job/{id}', [HrController::class, 'postJob'])->name('postJob');
        Route::get('/job-posting', [HrController::class, 'jobPostings'])->name('job-posting');
        
        // Job Management
        Route::get('/jobs/manage', [HrController::class, 'manageJobs'])->name('jobs.manage');
        Route::get('/jobs/applications', [HrController::class, 'viewApplications'])->name('jobs.applications');
        Route::post('/jobs/applications/{id}/status', [HrController::class, 'updateApplicationStatus'])
            ->name('jobs.application.status');
    });

    // Job Titles Routes - accessible by HOD and HR
    Route::middleware(['check.role:hod,hr'])->group(function () {
        Route::get('/job-titles', [JobTitleController::class, 'index'])->name('job-titles.index');
        Route::post('/job-titles', [JobTitleController::class, 'store'])->name('job-titles.store');
        Route::get('/job-titles/default', [JobTitleController::class, 'getDefaultTitles'])->name('job-titles.default');
        Route::post('/job-titles/custom', [JobTitleController::class, 'storeCustomTitle'])->name('job-titles.custom');
    });
});

// Add a test route temporarily to check user data
Route::get('/test-user', function() {
    dd(Auth::user()->toArray());
})->middleware('auth');

// Fallback Route
Route::fallback(function () {
    return redirect()->route('home');
});