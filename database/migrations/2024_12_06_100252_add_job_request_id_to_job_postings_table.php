<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First add the column without constraints
        Schema::table('job_postings', function (Blueprint $table) {
            $table->unsignedBigInteger('job_request_id')->nullable();
        });

        // Then add the foreign key constraint
        Schema::table('job_postings', function (Blueprint $table) {
            $table->foreign('job_request_id')
                  ->references('id')
                  ->on('job_requests')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropForeign(['job_request_id']);
            $table->dropColumn('job_request_id');
        });
    }
};