<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_request_id',
        'title',
        'description',
        'requirements',
        'status',
        'posted_at',
        'updated_at',
        'created_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'posted_at'
    ];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class, 'job_request_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_posting_id');
    }
}