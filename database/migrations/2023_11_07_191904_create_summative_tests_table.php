<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TeacherTopic;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('summative_tests', function (Blueprint $table) {
            $table->id();
            $table->string('title',500)->default("Test de totalizare");
            $table->integer("time")->default(300);
            $table->unsignedBigInteger("teacher_topic_id")->unique();
            $table->unsignedBigInteger("test_complexity_id"); 
            $table->timestamps();

            $table->foreign("teacher_topic_id")->references("id")->on("teacher_topics");
            $table->foreign("test_complexity_id")->references("id")->on("test_comlexities");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summative_tests');
    }
};
