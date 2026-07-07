<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('advert_adverts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('advert_categories');
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->string('title');
            $table->integer('price')->nullable();
            $table->string('address')->nullable();
            $table->text('content');
            $table->string('status', 16);
            $table->text('reject_reason')->nullable();
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
        });

        Schema::create('advert_advert_values', function (Blueprint $table) {
            $table->foreignId('advert_id')->constrained('advert_adverts')->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained('advert_attributes')->cascadeOnDelete();
            $table->string('value');
            $table->primary(['advert_id', 'attribute_id']);
        });

        Schema::create('advert_advert_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id')->constrained('advert_adverts')->cascadeOnDelete();
            $table->string('file');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advert_advert_photos');
        Schema::dropIfExists('advert_advert_values');
        Schema::dropIfExists('advert_adverts');
    }
};
