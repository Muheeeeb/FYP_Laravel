<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('job_requests', function (Blueprint $table) {
            $table->text('rejection_comment')->nullable();
            $table->timestamp('rejected_by_dean_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('job_requests', function (Blueprint $table) {
            $table->dropColumn('rejection_comment');
            $table->dropColumn('rejected_by_dean_at');
        });
    }
};
