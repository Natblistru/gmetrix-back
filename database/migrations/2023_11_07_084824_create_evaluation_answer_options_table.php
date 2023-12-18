<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EvaluationAnswer;
use App\Models\EvaluationOption;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluation_answer_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("evaluation_answer_id");
            $table->unsignedBigInteger("evaluation_option_id");
            $table->tinyInteger("status")->default(0);
            $table->timestamps();

            $table->foreign("evaluation_answer_id")->references("id")->on("evaluation_answers");
            $table->foreign("evaluation_option_id")->references("id")->on("evaluation_options");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_answer_options');
    }
};
