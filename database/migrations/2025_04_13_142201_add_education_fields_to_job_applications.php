<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Add education and additional fields if they don't exist
            if (!Schema::hasColumn('job_applications', 'university')) {
                $table->string('university')->nullable();
            }
            
            if (!Schema::hasColumn('job_applications', 'degree')) {
                $table->string('degree')->nullable();
            }
            
            if (!Schema::hasColumn('job_applications', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            
            if (!Schema::hasColumn('job_applications', 'end_date')) {
                $table->date('end_date')->nullable();
            }
            
            if (!Schema::hasColumn('job_applications', 'skills')) {
                $table->text('skills')->nullable();
            }
            
            if (!Schema::hasColumn('job_applications', 'cover_letter')) {
                $table->text('cover_letter')->nullable();
            }
            
            if (!Schema::hasColumn('job_applications', 'interview_status')) {
                $table->string('interview_status')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Only drop columns if they exist
            $columns = [
                'university', 'degree', 'start_date', 'end_date', 
                'skills', 'cover_letter', 'interview_status'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('job_applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
