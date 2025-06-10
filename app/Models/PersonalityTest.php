<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalityTest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'job_application_id',
        'results',
        'summary'
    ];
    
    protected $casts = [
        'results' => 'array'
    ];
    
    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }
    
    public function answers()
    {
        return $this->hasMany(PersonalityAnswer::class, 'personality_test_id');
    }
}
