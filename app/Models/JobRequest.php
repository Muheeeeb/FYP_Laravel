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
        'status',
        'rejection_comment'
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
}