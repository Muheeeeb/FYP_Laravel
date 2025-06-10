<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;

class ApplicationTrackingController extends Controller
{
    public function showTrackingForm()
    {
        return view('jobs.track-application');
    }

    public function trackApplication(Request $request)
    {
        try {
            // First check database connection
            try {
                \DB::connection()->getPdo();
            } catch (\Exception $e) {
                \Log::error('Database connection failed', [
                    'error' => $e->getMessage()
                ]);
                return back()->with('error', 'Unable to connect to the database. Please try again later.');
            }

            // Validate the request
            $validated = $request->validate([
                'email' => 'required|email',
                'phone' => 'required'
            ]);

            \Log::info('Starting application tracking', [
                'email' => $request->email,
                'phone' => $request->phone
            ]);

            // Query the application with error handling
            try {
                $application = JobApplication::with(['jobPosting' => function($query) {
                    $query->select('id', 'title');
                }])
                ->where('email', $request->email)
                ->where('phone', $request->phone)
                ->select(
                    'id', 
                    'job_id', 
                    'name', 
                    'email', 
                    'phone', 
                    'status', 
                    'created_at',
                    'interview_date',
                    'interview_time',
                    'interview_location',
                    'interview_instructions',
                    'status_updated_at'
                )
                ->first();

                \Log::info('Query executed successfully');

            } catch (\Exception $e) {
                \Log::error('Database query failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->with('error', 'Failed to retrieve application data. Please try again later.');
            }

            if (!$application) {
                \Log::warning('No application found', [
                    'email' => $request->email,
                    'phone' => $request->phone
                ]);
                return back()->with('error', 'No application found with these credentials. Please check your email and phone number.');
            }

            \Log::info('Application found', [
                'application_id' => $application->id,
                'status' => $application->status ?? 'no status'
            ]);

            // Get user-friendly status message
            try {
                $application->display_status = $this->getDisplayStatus($application->status);
                $application->status_class = $this->getStatusClass($application->status);
            } catch (\Exception $e) {
                \Log::error('Error processing status', [
                    'error' => $e->getMessage(),
                    'application_id' => $application->id
                ]);
                $application->display_status = 'Status Unavailable';
                $application->status_class = 'secondary';
            }

            // Format interview details if available
            if ($application->status === JobApplication::STATUS_INTERVIEW_SCHEDULED && $application->interview_date) {
                try {
                    \Log::info('Formatting interview details', [
                        'application_id' => $application->id,
                        'interview_date' => $application->interview_date,
                        'interview_time' => $application->interview_time
                    ]);
                    
                    $application->formatted_interview_date = date('F j, Y', strtotime($application->interview_date));
                    $application->formatted_interview_time = date('g:i A', strtotime($application->interview_time));
                    $application->has_interview_details = true;

                    \Log::info('Interview details formatted', [
                        'application_id' => $application->id,
                        'date' => $application->formatted_interview_date,
                        'time' => $application->formatted_interview_time
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error formatting interview details', [
                        'error' => $e->getMessage(),
                        'application_id' => $application->id,
                        'interview_date' => $application->interview_date,
                        'interview_time' => $application->interview_time
                    ]);
                    $application->has_interview_details = false;
                }
            } else {
                \Log::info('No interview details available', [
                    'application_id' => $application->id,
                    'status' => $application->status,
                    'has_interview_date' => $application->interview_date ? 'yes' : 'no'
                ]);
                $application->has_interview_details = false;
            }

            return view('jobs.application-status', compact('application'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error', [
                'errors' => $e->errors()
            ]);
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            \Log::error('Unhandled error in application tracking', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }

    private function getDisplayStatus($status)
    {
        try {
            return match ($status) {
                JobApplication::STATUS_APPLIED => 'Application Submitted',
                JobApplication::STATUS_ACCEPTED => 'Application Under Review by HR',
                JobApplication::STATUS_REJECTED => 'Application Not Selected',
                JobApplication::STATUS_INTERVIEW_SCHEDULED => 'Called for Interview',
                JobApplication::STATUS_INTERVIEWED => 'Interview Completed',
                JobApplication::STATUS_HIRED => 'Selected for Position',
                default => ucfirst($status),
            };
        } catch (\Exception $e) {
            \Log::error('Error in getDisplayStatus', [
                'status' => $status,
                'error' => $e->getMessage()
            ]);
            return ucfirst($status);
        }
    }

    private function getStatusClass($status)
    {
        try {
            return match ($status) {
                JobApplication::STATUS_APPLIED => 'primary',
                JobApplication::STATUS_ACCEPTED => 'info',
                JobApplication::STATUS_REJECTED => 'danger',
                JobApplication::STATUS_INTERVIEW_SCHEDULED => 'warning',
                JobApplication::STATUS_INTERVIEWED => 'info',
                JobApplication::STATUS_HIRED => 'success',
                default => 'secondary',
            };
        } catch (\Exception $e) {
            \Log::error('Error in getStatusClass', [
                'status' => $status,
                'error' => $e->getMessage()
            ]);
            return 'secondary';
        }
    }
}