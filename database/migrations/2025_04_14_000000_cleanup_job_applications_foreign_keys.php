<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // First, make sure job_posting_id exists and has all the data
            if (!Schema::hasColumn('job_applications', 'job_posting_id')) {
                $table->unsignedBigInteger('job_posting_id')->nullable();
                DB::statement('UPDATE job_applications SET job_posting_id = job_id WHERE job_posting_id IS NULL');
            }

            // Drop the old job_id foreign key if it exists
            if (Schema::hasColumn('job_applications', 'job_id')) {
                $table->dropForeign(['job_id']);
                $table->dropColumn('job_id');
            }

            // Make job_posting_id required and add foreign key
            DB::statement('UPDATE job_applications SET job_posting_id = job_id WHERE job_posting_id IS NULL');
            $table->foreign('job_posting_id')
                  ->references('id')
                  ->on('job_postings')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // In case we need to rollback, we'll restore the original structure
            if (!Schema::hasColumn('job_applications', 'job_id')) {
                $table->unsignedBigInteger('job_id');
                DB::statement('UPDATE job_applications SET job_id = job_posting_id');
                $table->foreign('job_id')
                      ->references('id')
                      ->on('job_postings')
                      ->onDelete('cascade');
            }
        });
    }
}; 