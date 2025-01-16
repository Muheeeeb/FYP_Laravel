<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('hod_id')->constrained('users')->onDelete('cascade');
            $table->string('position');
            $table->text('description');
            $table->string('status')->default('Pending'); // Pending, Approved by Dean, Posted by HR
            $table->timestamp('approved_by_dean_at')->nullable();
            $table->timestamp('posted_by_hr_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_requests');
    }
}
