<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الباقة
            $table->text('description')->nullable(); // وصف الباقة
            $table->decimal('price', 8, 2); // سعر الباقة
            $table->integer('duration_days'); // مدة الباقة بالأيام
            $table->boolean('is_active')->default(true); // حالة الباقة (مفعلة/غير مفعلة)
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
