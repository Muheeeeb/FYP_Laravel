<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'hod_id',
        'dean_id',
        'hr_id',
        'position',
        'description',
        'justification',
        'requirements',
        'status',
        'rejection_comment',
        'approved_by_dean_at',
        'posted_by_hr_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'approved_by_hod_at',
        'approved_by_dean_at',
        'rejected_by_hod_at',
        'rejected_by_dean_at',
        'posted_by_hr_at'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function hod()
    {
        return $this->belongsTo(User::class, 'hod_id');
    }

    public function dean()
    {
        return $this->belongsTo(User::class, 'dean_id');
    }

    public function hr()
    {
        return $this->belongsTo(User::class, 'hr_id');
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class);
    }

    public function jobPosting()
    {
        return $this->hasOne(JobPosting::class, 'job_request_id');
    }

    public function getStatusText()
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }
}