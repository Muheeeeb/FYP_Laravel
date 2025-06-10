<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('hod_id');
            $table->string('position');
            $table->text('description');
            $table->text('justification')->nullable();
            $table->text('rejection_comment')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamp('approved_by_dean_at')->nullable();
            $table->timestamp('posted_by_hr_at')->nullable();
            $table->timestamps();
            
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('hod_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_requests');
    }
}; 