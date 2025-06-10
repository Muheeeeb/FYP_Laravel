<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Notifications\ApplicationStatusUpdated;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JobApplication extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'job_posting_id',
        'job_id',
        'name',
        'email',
        'phone',
        'resume_path',
        'cover_letter',
        'university',
        'degree',
        'start_date',
        'end_date',
        'skills',
        'status',
        'match_percentage',
        'missing_keywords',
        'profile_summary',
        'is_ranked',
        'interview_date',
        'interview_time',
        'interview_location',
        'interview_instructions',
        'interview_status',
        'hod_feedback',
        'hr_feedback',
        'status_updated_at'
    ];

    protected $dates = [
        'status_updated_at',
        'interview_date',
        'interview_time'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'match_percentage' => 'float',
        'is_ranked' => 'boolean',
        'interview_time' => 'datetime',
        'status_updated_at' => 'datetime'
    ];

    // Define possible statuses
    const STATUS_APPLIED = 'Applied';
    const STATUS_ACCEPTED = 'Accepted by HOD';
    const STATUS_REJECTED = 'Rejected by HOD';
    const STATUS_INTERVIEW_SCHEDULED = 'Interview Scheduled';
    const STATUS_INTERVIEWED = 'Interviewed';
    const STATUS_HIRED = 'Hired';

    // Get all possible statuses
    public static function getStatuses()
    {
        return [
            self::STATUS_APPLIED => 'Applied',
            self::STATUS_ACCEPTED => 'Accepted by HOD',
            self::STATUS_REJECTED => 'Rejected by HOD',
            self::STATUS_INTERVIEW_SCHEDULED => 'Interview Scheduled',
            self::STATUS_INTERVIEWED => 'Interview Completed',
            self::STATUS_HIRED => 'Hired'
        ];
    }

    // Get status for display
    public function getDisplayStatus()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    // Get status class for UI
    public function getStatusClass()
    {
        return match ($this->status) {
            self::STATUS_HIRED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_ACCEPTED => 'info',
            self::STATUS_INTERVIEW_SCHEDULED => 'warning',
            self::STATUS_INTERVIEWED => 'primary',
            default => 'secondary',
        };
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    /**
     * Get the personality test associated with this application
     */
    public function personalityTest()
    {
        return $this->hasOne(PersonalityTest::class, 'job_application_id');
    }

    /**
     * Accept the candidate by HOD
     */
    public function accept($feedback = null)
    {
        try {
            \Log::info('Starting accept process', [
                'application_id' => $this->id,
                'current_status' => $this->status,
                'feedback' => $feedback
            ]);

            DB::beginTransaction();

            $this->status = self::STATUS_ACCEPTED;
            $this->hod_feedback = $feedback;
            $this->status_updated_at = now();
            $this->save();

            // Notify the candidate
            try {
                $this->notify(new ApplicationStatusUpdated($this));
                \Log::info('Acceptance notification sent successfully', [
                    'application_id' => $this->id,
                    'email' => $this->email
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send acceptance notification:', [
                    'application_id' => $this->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Don't throw the exception - we still want to accept the candidate even if notification fails
            }

            // Notify HR
            try {
                $hrUsers = User::where('role', User::ROLE_HR)->where('is_active', true)->get();
                foreach ($hrUsers as $hrUser) {
                    $hrUser->notify(new \App\Notifications\HrCandidateAccepted($this));
                }
                \Log::info('HR notification sent successfully', [
                    'application_id' => $this->id,
                    'hr_users' => $hrUsers->pluck('email')
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send HR notification:', [
                    'application_id' => $this->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Don't throw the exception - we still want to accept the candidate even if HR notification fails
            }

            DB::commit();

            \Log::info('Accept process completed successfully', [
                'application_id' => $this->id,
                'new_status' => $this->status
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in accept process:', [
                'application_id' => $this->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Reject the candidate by HOD
     */
    public function reject($feedback = null)
    {
        try {
            \Log::info('Starting reject process', [
                'application_id' => $this->id,
                'current_status' => $this->status,
                'feedback' => $feedback
            ]);

            DB::beginTransaction();

            $this->status = self::STATUS_REJECTED;
            $this->hod_feedback = $feedback;
            $this->status_updated_at = now();
            $this->save();

            // Notify the candidate
            try {
                $this->notify(new ApplicationStatusUpdated($this));
                \Log::info('Rejection notification sent successfully', [
                    'application_id' => $this->id,
                    'email' => $this->email
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send rejection notification:', [
                    'application_id' => $this->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Don't throw the exception - we still want to reject the candidate even if notification fails
            }

            DB::commit();

            \Log::info('Reject process completed successfully', [
                'application_id' => $this->id,
                'new_status' => $this->status
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in reject process:', [
                'application_id' => $this->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    // HR Actions
    public function scheduleInterview($date, $time, $location, $instructions = null)
    {
        try {
            \Log::info('Scheduling interview', [
                'application_id' => $this->id,
                'date' => $date,
                'time' => $time,
                'location' => $location
            ]);

            $this->update([
                'status' => self::STATUS_INTERVIEW_SCHEDULED,
                'interview_date' => $date,
                'interview_time' => $time,
                'interview_location' => $location,
                'interview_instructions' => $instructions,
                'status_updated_at' => now()
            ]);

            // Notify the candidate
            try {
                $this->notify(new ApplicationStatusUpdated($this));
                \Log::info('Interview notification sent', [
                    'application_id' => $this->id,
                    'email' => $this->email
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send interview notification:', [
                    'application_id' => $this->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Continue execution even if notification fails
                // Don't throw the exception here
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to schedule interview:', [
                'application_id' => $this->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function markInterviewed($feedback = null)
    {
        $this->update([
            'status' => self::STATUS_INTERVIEWED,
            'hr_feedback' => $feedback
        ]);
    }

    public function hire($feedback = null)
    {
        $this->update([
            'status' => self::STATUS_HIRED,
            'hr_feedback' => $feedback
        ]);
    }

    /**
     * Route notifications for the mail channel.
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    protected static function boot()
    {
        parent::boot();
    }
} 