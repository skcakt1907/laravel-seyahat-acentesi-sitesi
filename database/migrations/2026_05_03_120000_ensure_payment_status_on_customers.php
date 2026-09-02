<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('customers', 'payment_status')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('payment_status', 20)->default('unpaid')->after('phone');
                $table->index('payment_status');
            });
        }
    }

    public function down(): void
    {
        // Geri alma yok — production verisi kaybolmasın
    }
};
