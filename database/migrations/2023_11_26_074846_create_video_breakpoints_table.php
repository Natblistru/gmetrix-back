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
        Schema::create('video_breakpoints', function (Blueprint $table) {
            $table->id();
            $table->string("name",200);
            $table->string("time",10);
            $table->string("seconds",10);
            $table->unsignedBigInteger("video_id");
            $table->tinyInteger("status")->default(0);
            $table->timestamps();

            $table->foreign("video_id")->references("id")->on("videos");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_breakpoints');
    }
};
