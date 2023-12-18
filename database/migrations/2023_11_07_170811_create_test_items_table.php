<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TestComlexity;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('test_items', function (Blueprint $table) {
            $table->id();
            $table->string("task",1000);
            $table->enum('type', ["quiz", "check", "snap", "words", "dnd", "dnd_chrono","dnd_chrono_double", "dnd_group"]);
            $table->unsignedBigInteger("test_complexity_id");   
            $table->tinyInteger("status")->default(0);         
            $table->timestamps();

            $table->foreign("test_complexity_id")->references("id")->on("test_comlexities");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_items');
    }
};
