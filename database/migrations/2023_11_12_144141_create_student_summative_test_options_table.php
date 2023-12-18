<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SummativeTestItem;
use App\Models\TestItemOption;
use App\Models\Student;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('student_summative_test_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("student_id");
            $table->unsignedBigInteger("summative_test_item_id");
            $table->unsignedBigInteger("test_item_option_id");
            $table->decimal('score', 25, 2)->default(0.00);
            $table->tinyInteger("status")->default(0);
            $table->timestamps();

            $table->foreign("student_id")->references("id")->on("students");
            $table->foreign("summative_test_item_id")->references("id")->on("summative_test_items");
            $table->foreign("test_item_option_id")->references("id")->on("test_item_options");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_summative_test_options');
    }
};
