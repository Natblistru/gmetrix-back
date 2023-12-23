<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Evaluation;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluation_subjects', function (Blueprint $table) {
            $table->id();
            $table->enum('name', ['Subiectul I', 'Subiectul II', 'Subiectul III']);
            $table->integer("order_number")->default(1);
            $table->string("path",500);
            $table->unsignedBigInteger("evaluation_id");
            $table->tinyInteger("status")->default(0);
            $table->timestamps();

            $table->foreign("evaluation_id")->references("id")->on("evaluations");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_subjects');
    }
};
