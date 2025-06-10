<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->double('match_percentage', 8, 2)->nullable();
            $table->json('missing_keywords')->nullable();
            $table->text('profile_summary')->nullable();
            $table->boolean('is_ranked')->default(false);
        });
    }

    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['match_percentage', 'missing_keywords', 'profile_summary', 'is_ranked']);
        });
    }
}; 