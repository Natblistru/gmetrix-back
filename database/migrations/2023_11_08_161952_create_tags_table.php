<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('tag_name',50);
            $table->unsignedBigInteger('taggable_id');
            $table->string('taggable_type');
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
        Schema::dropIfExists('tags');
    }
};
