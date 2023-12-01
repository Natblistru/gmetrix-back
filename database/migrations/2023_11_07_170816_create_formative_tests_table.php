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
        Schema::create('formative_tests', function (Blueprint $table) {
            $table->id();
            $table->integer("order_number")->default(1);
            $table->string('title',500);
            $table->enum('type', ["quiz", "check", "snap", "words", "dnd", "dnd_chrono","dnd_chrono_double", "dnd_group"]);
            $table->unsignedBigInteger("teacher_topic_id");  
            $table->unsignedBigInteger("test_complexity_id");            
            $table->timestamps();

            $table->foreign("test_complexity_id")->references("id")->on("test_comlexities");
            $table->foreign("teacher_topic_id")->references("id")->on("teacher_topics"); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formative_tests');
    }
};
