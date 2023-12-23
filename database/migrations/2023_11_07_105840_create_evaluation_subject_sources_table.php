<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EvaluationSubject;
use App\Models\EvaluationSource;


return new class extends Migration
{

    public function up(): void
    {
        Schema::create('evaluation_subject_sources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("evaluation_subject_id");
            $table->unsignedBigInteger("evaluation_source_id");
            $table->integer("order_number")->default(1);
            $table->tinyInteger("status")->default(0);
            $table->timestamps();

            $table->foreign("evaluation_subject_id")->references("id")->on("evaluation_subjects");
            $table->foreign("evaluation_source_id")->references("id")->on("evaluation_sources");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_subject_sources');
    }
};
