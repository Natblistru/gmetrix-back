<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::table('test_items', function (Blueprint $table) {
        //     $table->json('content')->nullable();
        // });

        // Parcurgeți rândurile existente și transferați conținutul la cheia "en" în noua coloană JSON
        $items = DB::table('test_items')->get();
        foreach ($items as $item) {
            $content = ['en' => $item->task];
            DB::table('test_items')->where('id', $item->id)->update(['content' => json_encode($content)]);
        }

    }

};
