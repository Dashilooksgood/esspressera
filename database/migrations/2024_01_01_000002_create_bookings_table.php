<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('date');
            $table->string('time', 5); // format HH:MM
            $table->unsignedInteger('duration')->default(60); // menit
            $table->unsignedInteger('party_size')->default(1);
            $table->text('notes')->nullable();
            $table->string('status')->default('confirmed'); // pending, confirmed, cancelled
            $table->timestamps();

            $table->index(['date', 'room_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
