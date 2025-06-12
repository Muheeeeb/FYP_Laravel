<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->float('match_percentage')->nullable();
            $table->json('missing_keywords')->nullable();
            $table->text('profile_summary')->nullable();
            $table->boolean('is_ranked')->default(false);
            $table->text('hod_feedback')->nullable();
            $table->text('hr_feedback')->nullable();
            $table->timestamp('status_updated_at')->nullable();
            $table->timestamp('interview_date')->nullable();
            $table->timestamp('interview_time')->nullable();
            $table->string('interview_location')->nullable();
            $table->text('interview_instructions')->nullable();
            $table->string('interview_status')->nullable();
        });
    }

    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'match_percentage',
                'missing_keywords',
                'profile_summary',
                'is_ranked',
                'hod_feedback',
                'hr_feedback',
                'status_updated_at',
                'interview_date',
                'interview_time',
                'interview_location',
                'interview_instructions',
                'interview_status'
            ]);
        });
    }
}; 