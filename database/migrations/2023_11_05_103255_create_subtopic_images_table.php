<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Subtopic;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subtopic_images', function (Blueprint $table) {
            $table->id();
            $table->string("path",1000);
            $table->unsignedBigInteger("subtopic_id");  
            $table->timestamps();

            $table->foreign("subtopic_id")->references("id")->on("subtopics"); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtopic_images');
    }
};
