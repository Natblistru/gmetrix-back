<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SubjectStudyLevel;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->string("name",500);
            $table->integer("order_number")->default(1);
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
        Schema::dropIfExists('chapters');
    }
};
