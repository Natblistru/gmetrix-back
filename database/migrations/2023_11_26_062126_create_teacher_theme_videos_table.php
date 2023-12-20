<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Video;
use App\Models\Teacher;
use App\Models\ThemeLearningProgram;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teacher_theme_videos', function (Blueprint $table) {
            $table->id();
            $table->string("name",500);
            $table->unsignedBigInteger("video_id");
            $table->unsignedBigInteger("teacher_id");  
            $table->unsignedBigInteger("theme_learning_program_id"); 
            $table->tinyInteger("status")->default(0); 
            $table->timestamps();

            $table->foreign("video_id")->references("id")->on("videos");
            $table->foreign("teacher_id")->references("id")->on("teachers"); 
            $table->foreign("theme_learning_program_id")->references("id")->on("theme_learning_programs");   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_theme_videos');
    }
};
