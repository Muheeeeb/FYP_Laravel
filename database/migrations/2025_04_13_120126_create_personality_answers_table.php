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
        Schema::create('personality_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personality_test_id');
            $table->unsignedBigInteger('question_id');
            $table->text('answer');
            $table->integer('score')->nullable(); // For likert scale questions
            $table->timestamps();
            
            $table->foreign('personality_test_id')
                  ->references('id')
                  ->on('personality_tests')
                  ->onDelete('cascade');
                  
            $table->foreign('question_id')
                  ->references('id')
                  ->on('personality_questions')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personality_answers');
    }
};
