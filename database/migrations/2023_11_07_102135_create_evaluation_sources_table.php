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
            $table->integer("order_number")->default(1);
            $table->string("title",500)->nullable();
            $table->json('content')->nullable();
            $table->string("author",500)->nullable();
            $table->string("text_sourse",500);
            $table->tinyInteger("status")->default(0);
            $table->timestamps();
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
