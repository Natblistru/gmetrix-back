<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EvaluationSubject;
use App\Models\Theme;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('evaluation_items', function (Blueprint $table) {
            $table->id();
            $table->integer("order_number")->default(1);
            $table->string("task",1000);
            $table->string("statement",1000);
            $table->string("image_path",1000);
            $table->string("procent_paper",5);
            $table->string("editable_image_path",1000);
            $table->unsignedBigInteger("theme_id");
            $table->unsignedBigInteger("evaluation_subject_id");
            $table->timestamps();

            $table->foreign("theme_id")->references("id")->on("themes");
            $table->foreign("evaluation_subject_id")->references("id")->on("evaluation_subjects");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_items');
    }
};
