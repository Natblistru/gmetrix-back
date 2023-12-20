<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SubjectStudyLevel;
use App\Models\Theme;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('learning_programs', function (Blueprint $table) {
            $table->id();
            $table->string("name",200);
            $table->unsignedSmallInteger('year');
            $table->unsignedBigInteger("subject_study_level_id"); 
            $table->tinyInteger("status")->default(0);
            $table->timestamps();

            $table->foreign("subject_study_level_id")->references("id")->on("subject_study_levels");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_programs');
    }
};
