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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('restaurant_id')->constrained();
            $table->enum('occasion', ['birthday', 'anniversary', 'business_meeting', 'other'])->nullable();
            $table->enum('seating_preference', ['indoor', 'outdoor', 'window'])->nullable();
            $table->enum('special_requests', ['wheelchair_access', 'high_chair', 'none'])->nullable();
            $table->dateTime('booking_at');
            $table->integer('guests_count');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
