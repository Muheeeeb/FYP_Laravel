<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Add interview fields
            $table->date('interview_date')->nullable();
            $table->time('interview_time')->nullable();
            $table->string('interview_location')->nullable();
            
            // Add feedback fields
            $table->text('hod_feedback')->nullable();
            $table->text('hr_feedback')->nullable();
        });
    }

    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'interview_date',
                'interview_time',
                'interview_location',
                'hod_feedback',
                'hr_feedback'
            ]);
        });
    }
}; 