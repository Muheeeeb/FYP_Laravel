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
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ApplicationTrackingController;
use App\Models\JobPosting;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\Admin\AdminPasswordResetController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\PersonalityTestController;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home route
Route::get('/', function () {
    return view('home');
})->name('home');

// Chatbot route
Route::post('/chatbot', [ChatbotController::class, 'chat'])->name('chatbot.chat');

// Job Routes
Route::prefix('jobs')->name('jobs.')->group(function () {
    Route::get('/', [JobListingController::class, 'index'])->name('index');
    Route::get('/listings', [JobListingController::class, 'index'])->name('listings');
    Route::get('/{id}', [JobListingController::class, 'show'])->name('show');
    Route::get('/{id}/apply', [JobListingController::class, 'apply'])->name('apply');
    Route::post('/{id}/submit', [JobController::class, 'submit'])->name('submit');
});

// Add these routes outside any middleware group
Route::get('/track-application', [ApplicationTrackingController::class, 'showTrackingForm'])->name('track.form');
Route::post('/track-application', [ApplicationTrackingController::class, 'trackApplication'])->name('track.application');

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

// Verification routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/verify', [AuthController::class, 'showVerifyForm'])->name('verify.form');
    Route::post('/verify', [AuthController::class, 'verify'])->name('verify.code');
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
        Route::get('/forgot-password', [AdminPasswordResetController::class, 'showForgotForm'])
            ->name('password.request');
        Route::post('/forgot-password', [AdminPasswordResetController::class, 'sendResetLink'])
            ->name('password.email');
        Route::get('/reset-password/{token}', [AdminPasswordResetController::class, 'showResetForm'])
            ->name('password.reset');
        Route::post('/reset-password', [AdminPasswordResetController::class, 'resetPassword'])
            ->name('password.update');
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
    Route::middleware(['auth', 'role:hod'])->group(function () {
        Route::prefix('hod')->group(function () {
            Route::get('/dashboard', [HodController::class, 'dashboard'])->name('hod.dashboard');
            Route::get('/job-requests', [HodController::class, 'jobRequests'])->name('hod.job-requests');
            Route::get('/candidates', [HodController::class, 'candidates'])->name('hod.candidates');
            Route::post('/candidates/{id}/accept', [HodController::class, 'acceptCandidate'])->name('hod.accept-candidate');
            Route::post('/candidates/{id}/reject', [HodController::class, 'rejectCandidate'])->name('hod.reject-candidate');
        
        // Job Request Routes
            Route::get('/job-request/create', [HodController::class, 'createJobRequestForm'])->name('hod.createJobRequestForm');
            Route::post('/job-request', [HodController::class, 'createJobRequest'])->name('hod.createJobRequest');
            Route::get('/job-request/{id}/edit', [HodController::class, 'editJobRequest'])->name('hod.editJobRequest');
            Route::put('/job-requests/{id}', [HodController::class, 'updateJobRequest'])->name('hod.updateJobRequest');
            Route::delete('/job-request/{id}', [HodController::class, 'deleteJobRequest'])->name('deleteJobRequest');
            
            // Other routes
            Route::get('/application/{id}/resume', [HodController::class, 'viewResume'])->name('hod.application.resume');
            Route::get('/settings', [HodController::class, 'settings'])->name('hod.settings');
            Route::put('/settings', [HodController::class, 'updateSettings'])->name('hod.updateSettings');
            
            // Analytics
            Route::get('/analytics', [HodController::class, 'analytics'])->name('hod.analytics');
            Route::get('/application/{id}/personality-test', [HodController::class, 'getPersonalityTestResults'])->name('hod.application.personality-test');
            Route::get('/application/{id}/details', [HodController::class, 'getApplicationDetails'])->name('hod.application.details');
            Route::get('/applications/refresh-ranking/{id}', [HodController::class, 'refreshRanking'])->name('hod.applications.refresh-ranking');
            Route::get('/application/{id}/debug-personality-test', [HodController::class, 'debugPersonalityTest'])->name('hod.application.debug-personality-test');
            Route::get('/application/{id}/test-personality-data', [HodController::class, 'testPersonalityData'])->name('hod.application.test-personality-data');
            Route::get('/application/{id}/pure-json-test', function($id) {
                try {
                    $application = \App\Models\JobApplication::findOrFail($id);
                    $personalityTest = $application->personalityTest;
                    
                    $data = [
                        'success' => true,
                        'has_personality_test' => !is_null($personalityTest),
                        'application' => [
                            'id' => $application->id,
                            'name' => $application->name
                        ]
                    ];
                    
                    if ($personalityTest) {
                        $data['personality_test'] = [
                            'id' => $personalityTest->id,
                            'created_at' => $personalityTest->created_at->format('Y-m-d H:i:s'),
                            'has_data' => !empty($personalityTest->results)
                        ];
                    } else {
                        $data['personality_test'] = null;
                    }
                    
                    return response()->json($data);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'error' => $e->getMessage()
                    ], 500);
                }
            })->middleware(['auth', 'role:hod'])->name('hod.application.pure-json-test');
        });
    });

    // Dean Routes
    Route::middleware(['auth', 'role:dean'])->group(function () {
        Route::prefix('dean')->name('dean.')->group(function () {
            Route::get('/dashboard', [DeanController::class, 'dashboard'])->name('dashboard');
            Route::get('/analytics', [DeanController::class, 'analytics'])->name('analytics');
            Route::get('/settings', [DeanController::class, 'settings'])->name('settings');
            Route::put('/profile', [DeanController::class, 'updateProfile'])->name('updateProfile');
            Route::put('/password', [DeanController::class, 'updatePassword'])->name('updatePassword');
            Route::post('/approve-request/{id}', [DeanController::class, 'approveRequest'])->name('approveRequest');
            Route::post('/reject-request/{id}', [DeanController::class, 'rejectRequest'])->name('rejectRequest');
        });
    });

    // HR Routes
    Route::middleware(['auth', 'role:hr', 'prevent.back.history'])->group(function () {
        Route::prefix('hr')->name('hr.')->group(function () {
            // Dashboard
            Route::get('/dashboard', [HrController::class, 'dashboard'])->name('dashboard');
            
            // Job Management
            Route::get('/manage-jobs', [HrController::class, 'manageJobs'])->name('manage.jobs');
            Route::get('/job-posting', [HrController::class, 'jobPosting'])->name('job-posting');
            Route::post('/job-posting', [HrController::class, 'postJob'])->name('post-job');
            Route::post('/job/{id}/close', [HrController::class, 'closeJob'])->name('close.job');
            
            // Applications
            Route::get('/applications', [HrController::class, 'applications'])->name('applications');
            Route::get('/applications/job/{id}', [HrController::class, 'viewApplications'])->name('applications.job');
            Route::get('/applications/resume/{id}', [HrController::class, 'viewResume'])->name('applications.resume');
            Route::put('/applications/{application}/status', [HrController::class, 'updateApplicationStatus'])->name('applications.status');
            Route::get('/applications/export/{jobId?}', [HrController::class, 'exportApplications'])->name('applications.export');
            Route::get('/applications/refresh-ranking/{jobId}', [HrController::class, 'refreshRanking'])->name('applications.refresh-ranking');
            
            // Analytics
            Route::get('/analytics', [HrController::class, 'analytics'])->name('analytics');
        });
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

// Add this route temporarily for debugging
Route::get('/check-resumes', function() {
    $applications = \App\Models\JobApplication::select('id', 'name', 'email', 'resume_path')->get();
    
    $results = [];
    foreach($applications as $app) {
        $results[] = [
            'id' => $app->id,
            'name' => $app->name,
            'email' => $app->email,
            'resume_path' => $app->resume_path,
            'file_exists' => \Storage::disk('public')->exists($app->resume_path),
            'full_path' => storage_path('app/public/' . $app->resume_path)
        ];
    }
    
    dd($results); // This will display the results in a readable format
})->middleware(['auth', 'check.role:hr']);

// Job listings and applications
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/jobs/{id}/apply', [JobController::class, 'apply'])->name('jobs.apply');
Route::post('/jobs/{id}/submit', [JobController::class, 'submit'])->name('jobs.submit');

// Personality test routes - reordered to avoid conflicts
Route::get('/jobs/apply/success', [JobController::class, 'applicationSuccess'])->name('jobs.apply.success');
Route::get('/personality-test/{applicationId}', [PersonalityTestController::class, 'showTest'])->name('jobs.personality-test');
Route::post('/personality-test/{applicationId}', [PersonalityTestController::class, 'submitTest'])->name('jobs.personality-test.submit');

// Fallback Route
Route::fallback(function () {
    return redirect()->route('home');
});

// At the bottom of your routes file, add this temporarily
Route::get('/debug-routes', function () {
    $routes = Route::getRoutes();
    foreach ($routes as $route) {
        if (str_contains($route->uri, 'dean')) {
            echo $route->uri . ' - ' . $route->getName() . '<br>';
        }
    }
});

Route::post('/hr/post-job/{id}', [HrController::class, 'postJob'])
    ->name('hr.post-job')
    ->middleware(['auth', 'role:hr']);

// Add these new routes
Route::post('/subscribe', [SubscriberController::class, 'subscribe'])->name('subscribe');
Route::get('/unsubscribe/{email}', [SubscriberController::class, 'unsubscribe'])->name('unsubscribe');

// Add this temporary route to test email
Route::get('/test-mail', function() {
    try {
        $subscriber = \App\Models\Subscriber::first();
        
        if (!$subscriber) {
            return "No subscribers found!";
        }

        \Mail::raw('Test email from HireSmart', function($message) use ($subscriber) {
            $message->to($subscriber->email)
                    ->subject('Test Email');
        });

        return "Test email sent to: " . $subscriber->email;
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Add this temporary test route
Route::get('/test-notification', function() {
    try {
        $subscriber = \App\Models\Subscriber::first();
        $job = \App\Models\JobPosting::first();
        
        if ($subscriber && $job) {
            $subscriber->notify(new \App\Notifications\NewJobPosted($job));
            return "Test notification sent to " . $subscriber->email;
        }
        return "No subscriber or job found!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Add this temporary debug route
Route::get('/debug-data', function() {
    $subscribers = \App\Models\Subscriber::all();
    $jobRequests = \App\Models\JobRequest::where('status', 'Approved by Dean')->get();
    
    dd([
        'subscribers_count' => $subscribers->count(),
        'subscribers' => $subscribers->toArray(),
        'job_requests_count' => $jobRequests->count(),
        'job_requests' => $jobRequests->toArray()
    ]);
});

// Add this debug route
Route::get('/check-job-request/{id}', function($id) {
    try {
        $jobRequest = \App\Models\JobRequest::find($id);
        if (!$jobRequest) {
            return "Job request not found with ID: " . $id;
        }
        
        dd([
            'id' => $jobRequest->id,
            'title' => $jobRequest->title,
            'description' => $jobRequest->description,
            'requirements' => $jobRequest->requirements,
            'status' => $jobRequest->status,
            'department_id' => $jobRequest->department_id,
            'created_at' => $jobRequest->created_at,
            'updated_at' => $jobRequest->updated_at,
            'posted_by_hr_at' => $jobRequest->posted_by_hr_at,
            'approved_by_dean_at' => $jobRequest->approved_by_dean_at
        ]);
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Make sure your dean routes are protected with 2FA
Route::middleware(['auth', 'role:dean', '2fa'])->group(function () {
    Route::get('/dean/dashboard', [DeanController::class, 'dashboard'])->name('dean.dashboard');
    // ... other dean routes ...
});

// Replace the dean routes with this simpler version (without 2FA)
Route::middleware(['auth', 'role:dean'])->group(function () {
    Route::get('/dean/dashboard', [DeanController::class, 'dashboard'])->name('dean.dashboard');
    Route::get('/dean/job-requests', [DeanController::class, 'jobRequests'])->name('dean.job-requests');
    Route::post('/dean/job-requests/{id}/approve', [DeanController::class, 'approveJobRequest'])->name('dean.job-requests.approve');
    Route::post('/dean/job-requests/{id}/reject', [DeanController::class, 'rejectJobRequest'])->name('dean.job-requests.reject');
    // ... any other dean routes ...
});

Route::get('/test-job-email', function() {
    $subscriber = \App\Models\Subscriber::first();
    $jobPosting = \App\Models\JobPosting::first();
    
    if ($subscriber && $jobPosting) {
        \Mail::to($subscriber->email)
            ->send(new \App\Mail\NewJobPostedMail($jobPosting));
        return "Test email sent to " . $subscriber->email;
    }
    return "No subscriber or job posting found";
});

// Test route for email verification
Route::get('/test-email', function() {
    try {
        $subscriber = \App\Models\Subscriber::first();
        
        if (!$subscriber) {
            return "No subscribers found in database. Please add a subscriber first.";
        }

        \Mail::raw('Test email from HireSmart System', function($message) use ($subscriber) {
            $message->to($subscriber->email)
                    ->subject('Test Email');
        });

        return "Test email sent to: " . $subscriber->email;
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Add this temporary debug route
Route::get('/debug-post-job/{id}', [HrController::class, 'postJob']);

// Add this test route
Route::get('/test-job-email/{id}', function($id) {
    try {
        $jobPosting = \App\Models\JobPosting::findOrFail($id);
        $subscriber = \App\Models\Subscriber::first();
        
        if (!$subscriber) {
            return "No subscribers found. Please add a subscriber first.";
        }

        \Mail::send('emails.jobs.new-posting', 
            ['jobPosting' => $jobPosting], 
            function($message) use ($subscriber) {
                $message->to($subscriber->email)
                        ->subject('Test - New Job Posting!');
            }
        );

        return "Test email sent to " . $subscriber->email . " for job: " . $jobPosting->title;
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/debug-jobs', function() {
    $jobs = \App\Models\JobPosting::orderBy('posted_at', 'desc')
                                  ->orderBy('created_at', 'desc')
                                  ->get();
    return $jobs->map(function($job) {
        return [
            'id' => $job->id,
            'title' => $job->title,
            'created_at' => $job->created_at
        ];
    });
});

// HR Routes
Route::middleware(['auth', 'role:hr'])->group(function () {
    Route::post('/hr/post-job/{id}', [HrController::class, 'postJob'])->name('hr.post-job');
});

// Add middleware to protected routes
Route::middleware(['auth', 'verify.2fa'])->group(function () {
    Route::get('/hr/dashboard', [HrController::class, 'dashboard'])->name('hr.dashboard');
    Route::get('/dean/dashboard', [DeanController::class, 'dashboard'])->name('dean.dashboard');
    Route::get('/hod/dashboard', [HodController::class, 'dashboard'])->name('hod.dashboard');
    // ... your other protected routes ...
});

// Keep verification routes outside the middleware group
Route::get('/verify', [AuthController::class, 'showVerifyForm'])->name('verify.form');
Route::post('/verify', [AuthController::class, 'verify'])->name('verify.code');

// Add new route for /chat endpoint
Route::post('/chat', [ChatbotController::class, 'sendMessage'])
    ->name('chat.message');

// If in production, apply SSL middleware to all routes
if (app()->environment('production')) {
    URL::forceScheme('https');
}

// CSRF Token Route
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

// Test route for scheduling interview
Route::get('/test-interview-notification', function() {
    try {
        $application = \App\Models\JobApplication::first();
        
        if (!$application) {
            return "No applications found. Please create an application first.";
        }

        $application->scheduleInterview(
            now()->addDays(2)->format('Y-m-d'),
            now()->addDays(2)->setHour(10)->format('H:i:s'),
            'Conference Room A',
            'Please bring your original documents and portfolio.'
        );

        return "Interview scheduled and notification sent to " . $application->email;
    } catch (\Exception $e) {
        \Log::error('Test interview notification error: ' . $e->getMessage());
        return "Error: " . $e->getMessage();
    }
});