<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\JobRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'hod_count' => User::where('role', 'hod')->count(),
            'dean_count' => User::where('role', 'dean')->count(),
            'hr_count' => User::where('role', 'hr')->count(),
            'total_departments' => Department::count(),
            'pending_requests' => JobRequest::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function profile()
    {
        $admin = Auth::user();
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
            $admin->password = Hash::make($request->new_password);
        }

        $admin->save();

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }

    public function analytics()
    {
        // Basic Statistics
        $stats = [
            'total_job_requests' => JobRequest::count(),
            'pending_requests' => JobRequest::where('status', 'pending')->count(),
            'approved_requests' => JobRequest::where('status', 'approved_by_dean')->count(),
            'posted_requests' => JobRequest::whereNotNull('posted_by_hr_at')->count()
        ];

        // Monthly Trend
        $monthlyStats = JobRequest::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $item->month_name = date('F', mktime(0, 0, 0, $item->month, 1));
                return $item;
            });

        // Department Statistics
        $departmentStats = Department::withCount([
            'jobRequests as total_requests',
            'jobRequests as approved_requests' => function ($query) {
                $query->where('status', 'approved_by_dean');
            },
            'jobRequests as posted_requests' => function ($query) {
                $query->whereNotNull('posted_by_hr_at');
            }
        ])->get();

        // HOD Statistics
        $hodStats = User::where('role', 'hod')
            ->withCount([
                'jobRequests as total_requests',
                'jobRequests as approved_requests' => function ($query) {
                    $query->where('status', 'approved_by_dean');
                },
                'jobRequests as posted_requests' => function ($query) {
                    $query->whereNotNull('posted_by_hr_at');
                }
            ])
            ->get()
            ->map(function ($hod) {
                $hod->approval_rate = $hod->total_requests > 0 
                    ? round(($hod->approved_requests / $hod->total_requests) * 100, 1) 
                    : 0;
                return $hod;
            });

        // Status Distribution
        $statusDistribution = JobRequest::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Recent Activity
        $recentActivity = JobRequest::with(['department', 'hod'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.analytics', compact(
            'stats',
            'monthlyStats',
            'departmentStats',
            'hodStats',
            'statusDistribution',
            'recentActivity'
        ));
    }

    public function settings()
    {
        $settings = [
            'site_name' => config('app.name'),
            'admin_email' => config('mail.from.address'),
            'mail_driver' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_encryption' => config('mail.mailers.smtp.encryption'),
            'approval_workflow' => config('job-requests.approval_workflow', 'simple'),
            'auto_post_delay' => config('job-requests.auto_post_delay', 24),
            'email_notifications' => config('notifications.email', true),
            'system_notifications' => config('notifications.system', true),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'admin_email' => 'required|email',
        ]);

        try {
            $this->updateEnvFile('APP_NAME', '"'.$request->site_name.'"');
            $this->updateEnvFile('MAIL_FROM_ADDRESS', $request->admin_email);
            
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            return redirect()->route('admin.settings')
                ->with('success', 'General settings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Settings update failed: ' . $e->getMessage());
            return redirect()->route('admin.settings')
                ->with('error', 'Failed to update settings. Please check permissions.');
        }
    }

    public function updateEmailSettings(Request $request)
    {
        $request->validate([
            'mail_driver' => 'required|in:smtp,sendmail',
            'mail_host' => 'required_if:mail_driver,smtp',
            'mail_port' => 'required_if:mail_driver,smtp|numeric',
            'mail_username' => 'required_if:mail_driver,smtp|email',
            'mail_password' => 'required_if:mail_driver,smtp',
            'mail_encryption' => 'required_if:mail_driver,smtp|in:tls,ssl',
        ]);

        try {
            $this->updateEnvFile('MAIL_MAILER', $request->mail_driver);
            $this->updateEnvFile('MAIL_HOST', $request->mail_host);
            $this->updateEnvFile('MAIL_PORT', $request->mail_port);
            $this->updateEnvFile('MAIL_USERNAME', $request->mail_username);
            $this->updateEnvFile('MAIL_PASSWORD', $request->mail_password);
            $this->updateEnvFile('MAIL_ENCRYPTION', $request->mail_encryption);
            $this->updateEnvFile('MAIL_FROM_ADDRESS', $request->mail_username);

            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            return redirect()->route('admin.settings')
                ->with('success', 'Email settings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Email settings update failed: ' . $e->getMessage());
            return redirect()->route('admin.settings')
                ->with('error', 'Failed to update email settings.');
        }
    }

    public function updateNotificationSettings(Request $request)
    {
        try {
            Config::set('notifications.email', $request->has('email_notifications'));
            Config::set('notifications.system', $request->has('system_notifications'));

            return redirect()->route('admin.settings')
                ->with('success', 'Notification settings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Notification settings update failed: ' . $e->getMessage());
            return redirect()->route('admin.settings')
                ->with('error', 'Failed to update notification settings.');
        }
    }

    public function updateJobRequestSettings(Request $request)
    {
        $request->validate([
            'approval_workflow' => 'required|in:simple,advanced',
            'auto_post_delay' => 'required|numeric|min:1',
        ]);

        try {
            Config::set('job-requests.approval_workflow', $request->approval_workflow);
            Config::set('job-requests.auto_post_delay', $request->auto_post_delay);

            return redirect()->route('admin.settings')
                ->with('success', 'Job request settings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Job request settings update failed: ' . $e->getMessage());
            return redirect()->route('admin.settings')
                ->with('error', 'Failed to update job request settings.');
        }
    }

    private function updateEnvFile($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            if (strpos($content, $key . '=') !== false) {
                $content = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=" . $value,
                    $content
                );
            } else {
                $content .= "\n{$key}=" . $value;
            }

            file_put_contents($path, $content);

            Log::info('Updated .env file', [
                'key' => $key,
                'value' => $value,
                'success' => true
            ]);
        }
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $reportData = JobRequest::whereBetween('created_at', [
            $request->start_date,
            $request->end_date
        ])->with(['department', 'hod'])->get();

        return back()->with('success', 'Report generated successfully');
    }

    public function downloadReport($reportId)
    {
        // Add report download logic here
        return response()->download($pathToFile);
    }
}