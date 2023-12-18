<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\StudyLevel;
use App\Models\Subject;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subject_study_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("study_level_id");
            $table->unsignedBigInteger("subject_id");  
            $table->string("name",200);
            $table->string("path",200);
            $table->string("img",200);
            $table->tinyInteger("status")->default(0);

            $table->timestamps();

            $table->foreign("study_level_id")->references("id")->on("study_levels");
            $table->foreign("subject_id")->references("id")->on("subjects"); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_study_levels');
    }
};
