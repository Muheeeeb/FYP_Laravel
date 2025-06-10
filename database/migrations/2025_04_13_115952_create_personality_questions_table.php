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
        Schema::create('personality_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->enum('type', ['multiple_choice', 'likert_scale'])->default('likert_scale');
            $table->json('options')->nullable(); // For multiple choice questions
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personality_questions');
    }
};
