<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Chapter;

return new class extends Migration
{

    public function up(): void    {
        Schema::create('themes', function (Blueprint $table) {
          $table->id();
          $table->string("name",500);
          $table->string("path",200);
          $table->integer("order_number")->default(1);
          $table->unsignedBigInteger("chapter_id");
          $table->timestamps();
  
          $table->foreign("chapter_id")->references("id")->on("chapters");
       });
  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
