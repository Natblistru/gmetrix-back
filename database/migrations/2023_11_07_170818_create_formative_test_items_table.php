<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\FormativeTest;
use App\Models\TestItem;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('formative_test_items', function (Blueprint $table) {
            $table->id();
            $table->integer("order_number")->default(1);
            $table->unsignedBigInteger("formative_test_id");
            $table->unsignedBigInteger("test_item_id");
            $table->tinyInteger("status")->default(0);
            $table->timestamps();

            $table->foreign("formative_test_id")->references("id")->on("formative_tests");
            $table->foreign("test_item_id")->references("id")->on("test_items");

            // Adăugarea cheii unică compuse
            $table->unique(['formative_test_id', 'test_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formative_test_items');
    }
};
