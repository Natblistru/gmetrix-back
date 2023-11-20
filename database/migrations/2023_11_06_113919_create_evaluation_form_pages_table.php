<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EvaluationItem;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluation_form_pages', function (Blueprint $table) {
            $table->id();
            $table->integer("order_number")->default(1);
            $table->string("task",1000);
            $table->json('hint')->nullable();
            $table->unsignedBigInteger("evaluation_item_id");
            $table->timestamps();

            $table->foreign("evaluation_item_id")->references("id")->on("evaluation_items");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_form_pages');
    }
};
