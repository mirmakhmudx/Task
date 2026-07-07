<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banner_banners', function (Blueprint $table) {
            $table->id();

            // Kim yaratgan — user o'chsa, banneri ham o'chadi
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Banner ham Advert bilan bir xil kategoriyalarni ishlatadi (advert_categories)
            $table->foreignId('category_id')->constrained('advert_categories');

            // Region ixtiyoriy: null bo'lsa — "barcha regionlar uchun" degani
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();

            $table->string('name');             // banner nomi
            $table->integer('limit');           // LIMIT: necha marta ko'rsatilsa yopiladi
            $table->string('url');              // bosilganda o'tadigan havola
            $table->string('format');           // o'lcham, masalan "240x400"
            $table->string('file')->nullable(); // rasm yo'li (storage/banners/...)

            $table->string('status', 16);       // draft / moderation / wait_pay / active / closed
            $table->integer('views')->default(0); // VIEWS: hozirgacha necha marta ko'rsatildi (hisoblagich)

            $table->text('reject_reason')->nullable();  // admin rad etsa — sababi
            $table->timestamp('published_at')->nullable(); // to'langach faollashgan vaqt
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_banners');
    }
};
