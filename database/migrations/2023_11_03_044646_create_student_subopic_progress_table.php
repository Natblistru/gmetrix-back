<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Student;
use App\Models\Subtopic;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_subopic_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("student_id");
            $table->unsignedBigInteger("subtopic_id");  
            $table->integer("progress_percentage")->default(0);
            $table->tinyInteger("status")->default(0);
            $table->timestamps();

            $table->foreign("student_id")->references("id")->on("students");
            $table->foreign("subtopic_id")->references("id")->on("subtopics"); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_subopic_progress');
    }
};
