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
        Schema::create('flip_cards', function (Blueprint $table) {
            $table->id();
            $table->string("task",500);
            $table->string("answer",5000);
            $table->unsignedBigInteger("teacher_topic_id");  
            $table->timestamps();

            $table->foreign("teacher_topic_id")->references("id")->on("teacher_topics"); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flip_cards');
    }
};
