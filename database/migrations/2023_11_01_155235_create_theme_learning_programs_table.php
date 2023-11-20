<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\LearningProgram;
use App\Models\Theme;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theme_learning_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("learning_program_id"); 
            $table->unsignedBigInteger("theme_id");  
            $table->timestamps();

            $table->foreign("learning_program_id")->references("id")->on("learning_programs");
            $table->foreign("theme_id")->references("id")->on("themes");   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_learning_programs');
    }
};
