<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalityQuestion extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'question',
        'type',
        'options',
        'order',
        'is_active'
    ];
    
    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean'
    ];
    
    public function answers()
    {
        return $this->hasMany(PersonalityAnswer::class, 'question_id');
    }
}
