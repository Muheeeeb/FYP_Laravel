<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    protected $fillable = [
        'request_id',
        'title',
        'description',
        'requirements',
        'posted_at',
        'status', // Add status field
        'department_id' // Add direct department relationship
    ];

    protected $dates = [
        'posted_at'
    ];

    // Define status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Define available job titles
    const JOB_TITLES = [
        'Junior Lecturer',
        'Support Office',
        'Senior Lecturer',
        'Assistant Professor',
        'Professor'
    ];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class, 'request_id');
    }

    // Simplified direct department relationship
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Helper method to get status badge class
    public function getStatusBadgeClass()
    {
        return [
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_APPROVED => 'badge-success',
            self::STATUS_REJECTED => 'badge-danger',
        ][$this->status] ?? 'badge-secondary';
    }

    // Helper method to get formatted status text
    public function getStatusText()
    {
        return ucfirst($this->status);
    }

    // Helper method to check if title is custom
    public function isCustomTitle()
    {
        return !in_array($this->title, self::JOB_TITLES);
    }

    // Update this relationship with explicit foreign key
    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_posting_id');
    }
}