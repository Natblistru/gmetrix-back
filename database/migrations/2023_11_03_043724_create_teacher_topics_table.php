<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Teacher;
use App\Models\Topic;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teacher_topics', function (Blueprint $table) {
            $table->id();
            $table->string("name",500);
            $table->integer("order_number")->default(1);
            $table->unsignedBigInteger("teacher_id");
            $table->unsignedBigInteger("topic_id");  
            $table->tinyInteger("status")->default(0);
            $table->timestamps();

            $table->foreign("teacher_id")->references("id")->on("teachers");
            $table->foreign("topic_id")->references("id")->on("topics"); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_topics');
    }
};
