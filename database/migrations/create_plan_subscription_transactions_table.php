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
        Schema::create(config('laravel-subscriptions.tables.subscription_transactions', 'transactions'), function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('notchpay_reference')->nullable()->unique();
            $table->foreignId('subscription_id')->nullable();
            $table->double('amount')->nullable();
            $table->string('currency', 3);
            $table->string('status')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
