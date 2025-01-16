<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    protected $fillable = [
        'title',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class, 'title', 'title');
    }
}