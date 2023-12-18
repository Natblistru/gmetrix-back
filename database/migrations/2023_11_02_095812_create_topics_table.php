<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ThemeLearningProgram;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string("name",500);
            $table->string("path",500);
            $table->integer("order_number")->default(1);
            $table->unsignedBigInteger("theme_learning_program_id"); 
            $table->tinyInteger("status")->default(0);   
            $table->timestamps();

            $table->foreign("theme_learning_program_id")->references("id")->on("theme_learning_programs");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
