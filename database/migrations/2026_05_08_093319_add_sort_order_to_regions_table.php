<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('parent_id');
        });

        // Mavjud regionlarga id bo'yicha sort_order berish
        DB::statement('UPDATE regions SET sort_order = id');
    }

    public function down(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
