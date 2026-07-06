<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advert_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained('advert_categories')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('type');
            $table->boolean('required')->default(false);
            $table->string('default')->nullable();
            $table->json('variants')->nullable();
            $table->integer('sort')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advert_attributes');
    }
};
