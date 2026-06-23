<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // E'lon bo'yicha dialoglar (egasi <-> xaridor)
        Schema::create('advert_dialogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id')->constrained('advert_adverts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();   // e'lon egasi
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete(); // yozgan odam (xaridor)
            $table->integer('user_new_messages')->default(0);   // egasi uchun o'qilmagan
            $table->integer('client_new_messages')->default(0); // xaridor uchun o'qilmagan
            $table->timestamps();

            $table->unique(['advert_id', 'client_id']); // bitta e'lon + xaridor = bitta dialog
        });

        // Dialog xabarlari
        Schema::create('advert_dialog_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dialog_id')->constrained('advert_dialogs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users'); // kim yozdi
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advert_dialog_messages');
        Schema::dropIfExists('advert_dialogs');
    }
};
