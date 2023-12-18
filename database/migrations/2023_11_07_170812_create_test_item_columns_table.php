<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TestItem;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_item_columns', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger("order_number")->default(0);
            $table->string('title',100);
            $table->unsignedBigInteger("test_item_id");
            $table->tinyInteger("status")->default(0);
            $table->timestamps();

            $table->foreign("test_item_id")->references("id")->on("test_items");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_item_columns');
    }
};
