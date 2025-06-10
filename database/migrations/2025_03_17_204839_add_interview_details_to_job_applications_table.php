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
            // Only add columns that don't exist
            if (!Schema::hasColumn('job_applications', 'interview_instructions')) {
                $table->text('interview_instructions')->nullable();
            }
            if (!Schema::hasColumn('job_applications', 'status_updated_at')) {
                $table->timestamp('status_updated_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'interview_instructions',
                'status_updated_at'
            ]);
        });
    }
};
