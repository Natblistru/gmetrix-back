<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TestItem;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('test_item_options', function (Blueprint $table) {
            $table->id();
            $table->string('option',500);
            $table-> string('explanation',500)->nullable();
            $table->json('text_additional')->nullable();
            $table->unsignedSmallInteger('correct')->default(0);
            $table->unsignedBigInteger("test_item_id");            
            $table->timestamps();

            $table->foreign("test_item_id")->references("id")->on("test_items");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_options');
    }
};
