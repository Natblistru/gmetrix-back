<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Student;
use App\Models\EvaluationAnswer;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('student_evaluation_answers', function (Blueprint $table) {
            $table->id();
            $table->integer("points")->default(0);
            $table->unsignedBigInteger("student_id");
            $table->unsignedBigInteger("evaluation_answer_id");
            $table->timestamps();

            $table->foreign("student_id")->references("id")->on("students");
            $table->foreign("evaluation_answer_id")->references("id")->on("evaluation_answers");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_evaluation_answers');
    }
};
