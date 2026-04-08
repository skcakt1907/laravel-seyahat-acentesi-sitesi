<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payments')) {
            return;
        }

        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->string('order_id', 50);
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('currency', 10)->default('GBP');
            $table->string('provider', 30)->default('garanti');
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('card_last4', 4)->nullable();
            $table->string('card_holder', 100)->nullable();
            $table->string('transaction_id', 100)->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('customer_id');
            $table->index('order_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
