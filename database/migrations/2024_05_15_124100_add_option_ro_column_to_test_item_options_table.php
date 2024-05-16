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
        Schema::table('test_item_options', function (Blueprint $table) {
            $table->string('option_ro', 1000)->after('option')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_item_options', function (Blueprint $table) {
            $table->dropColumn('option_ro'); 
        });
    }
};
