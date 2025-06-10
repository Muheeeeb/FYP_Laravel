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
            // Add job_posting_id column if it doesn't exist
            if (!Schema::hasColumn('job_applications', 'job_posting_id')) {
                $table->foreignId('job_posting_id')->nullable()->after('id');
            }
            
            // Add resume_path column if it doesn't exist
            if (!Schema::hasColumn('job_applications', 'resume_path')) {
                $table->string('resume_path')->nullable()->after('cv_path');
            }
            
            // Add education column if it doesn't exist
            if (!Schema::hasColumn('job_applications', 'education')) {
                $table->text('education')->nullable()->after('status');
            }
            
            // Add experience column if it doesn't exist
            if (!Schema::hasColumn('job_applications', 'experience')) {
                $table->text('experience')->nullable()->after('education');
            }
            
            // Add skills column if it doesn't exist
            if (!Schema::hasColumn('job_applications', 'skills')) {
                $table->text('skills')->nullable()->after('experience');
            }
            
            // Add match_percentage column if it doesn't exist
            if (!Schema::hasColumn('job_applications', 'match_percentage')) {
                $table->float('match_percentage')->nullable()->after('skills');
            }
            
            // Add profile_summary column if it doesn't exist
            if (!Schema::hasColumn('job_applications', 'profile_summary')) {
                $table->text('profile_summary')->nullable()->after('match_percentage');
            }
            
            // Add is_ranked column if it doesn't exist
            if (!Schema::hasColumn('job_applications', 'is_ranked')) {
                $table->boolean('is_ranked')->default(false)->after('profile_summary');
            }
            
            // Add cover_letter column if it doesn't exist
            if (!Schema::hasColumn('job_applications', 'cover_letter')) {
                $table->text('cover_letter')->nullable()->after('cv_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Only drop columns that exist
            $columns = [
                'job_posting_id',
                'resume_path',
                'education',
                'experience',
                'skills',
                'match_percentage',
                'profile_summary',
                'is_ranked',
                'cover_letter'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('job_applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
