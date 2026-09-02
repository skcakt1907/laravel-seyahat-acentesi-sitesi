<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'charge_amount')) {
                $table->decimal('charge_amount', 12, 2)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('payments', 'charge_currency')) {
                $table->string('charge_currency', 10)->nullable()->after('charge_amount');
            }
            if (!Schema::hasColumn('payments', 'fx_rate')) {
                $table->decimal('fx_rate', 12, 6)->nullable()->after('charge_currency');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            foreach (['charge_amount', 'charge_currency', 'fx_rate'] as $col) {
                if (Schema::hasColumn('payments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
