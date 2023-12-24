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
        Schema::create('evaluation_sources', function (Blueprint $table) {
            $table->id();
            $table->string("name",500);
            $table->string("title",500)->nullable();
            $table->json('content');
            $table->string("author",500)->nullable();
            $table->string("text_sourse",500)->nullable();
            $table->unsignedBigInteger("theme_id");
            $table->tinyInteger("status")->default(0);
            $table->timestamps();

            $table->foreign("theme_id")->references("id")->on("themes");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_sources');
    }
};
