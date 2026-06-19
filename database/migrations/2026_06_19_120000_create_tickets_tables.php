<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tiketlar
        Schema::create('ticket_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject');                 // mavzu
            $table->text('content');                   // matn
            $table->string('status', 16);              // open / approved / closed
            $table->timestamps();
        });

        // Status tarixi (Open -> Approved -> Closed, kim va qachon o'zgartirgani)
        Schema::create('ticket_ticket_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('ticket_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->string('status', 16);
            $table->timestamp('created_at')->nullable();
        });

        // Tiket ichidagi xabarlar (user <-> admin yozishmasi)
        Schema::create('ticket_ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('ticket_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_ticket_messages');
        Schema::dropIfExists('ticket_ticket_statuses');
        Schema::dropIfExists('ticket_tickets');
    }
};
