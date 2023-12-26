<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TeacherTopic;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subtopics', function (Blueprint $table) {
            $table->id();
            $table->string("name",500);
            $table->unsignedBigInteger("teacher_topic_id");  
            $table->string("audio_path",1000)->nullable();;    
            $table->tinyInteger("status")->default(0);       
            $table->timestamps();

            $table->foreign("teacher_topic_id")->references("id")->on("teacher_topics"); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtopics');
    }
};
