<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateJobTitlesTable extends Migration
{
    public function up()
    {
        Schema::create('job_titles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->boolean('is_default')->default(true);
            $table->timestamps();
        });

        // Insert default job titles
        DB::table('job_titles')->insert([
            ['title' => 'Junior Lecturer', 'is_default' => true],
            ['title' => 'Support Office', 'is_default' => true],
            ['title' => 'Senior Lecturer', 'is_default' => true],
            ['title' => 'Assistant Professor', 'is_default' => true],
            ['title' => 'Professor', 'is_default' => true],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('job_titles');
    }
}