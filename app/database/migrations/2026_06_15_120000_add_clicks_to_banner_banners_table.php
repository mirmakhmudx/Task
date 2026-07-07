<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('banner_banners', function (Blueprint $table) {
            $table->integer('clicks')->default(0)->after('views');
        });
    }

    public function down(): void
    {
        Schema::table('banner_banners', function (Blueprint $table) {
            $table->dropColumn('clicks');
        });
    }
};
