<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalityAnswer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'personality_test_id',
        'question_id',
        'answer',
        'score'
    ];
    
    public function test()
    {
        return $this->belongsTo(PersonalityTest::class, 'personality_test_id');
    }
    
    public function question()
    {
        return $this->belongsTo(PersonalityQuestion::class, 'question_id');
    }
}
