<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('test_items', function (Blueprint $table) {
            $table->string("task_ro", 1000)->nullable();
            $table->string("image_path", 1000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_items', function (Blueprint $table) {
            $table->dropColumn('task_ro');
            $table->dropColumn('image_path');
        });
    }
};
