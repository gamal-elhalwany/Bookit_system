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
        Schema::create('subscriptions_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id');
            $table->unsignedBigInteger('restaurant_id');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->enum('payment_gateway', ['stripe', 'paymob', 'bank_transfer']);
            $table->string('transaction_id')->unique();
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->enum('status', ['completed', 'failed', 'pending'])->default('pending');
            $table->string('receipt_url')->nullable();
            $table->string('payer_email')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions_payments');
    }
};
