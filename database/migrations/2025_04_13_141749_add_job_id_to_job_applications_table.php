<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Add job_id column if it doesn't exist
            if (!Schema::hasColumn('job_applications', 'job_id')) {
                $table->unsignedBigInteger('job_id')->nullable();
                
                // Populate existing records with job_posting_id
                DB::statement('UPDATE job_applications SET job_id = job_posting_id WHERE job_id IS NULL');
                
                // After populating, make it required
                DB::statement('ALTER TABLE job_applications MODIFY job_id BIGINT UNSIGNED NOT NULL');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Don't remove job_id if it might still be in use
        });
    }
};
