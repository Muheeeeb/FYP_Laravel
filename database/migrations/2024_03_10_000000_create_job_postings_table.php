<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('department')->nullable();
            $table->unsignedBigInteger('job_request_id')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
            
            $table->foreign('job_request_id')
                  ->references('id')
                  ->on('job_requests')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_postings');
    }
}; 