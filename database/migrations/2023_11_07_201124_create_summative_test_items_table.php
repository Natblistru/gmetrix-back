<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SummativeTest;
use App\Models\TestItem;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('summative_test_items', function (Blueprint $table) {
            $table->id();
            $table->integer("order_number")->default(1);
            $table->unsignedBigInteger("summative_test_id");
            $table->unsignedBigInteger("test_item_id");
            $table->timestamps();

            $table->foreign("summative_test_id")->references("id")->on("summative_tests");
            $table->foreign("test_item_id")->references("id")->on("test_items");

            // // Adăugarea cheii unică compuse
            // $table->unique(['summative_test_id', 'test_item_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summative_test_items');
    }
};
