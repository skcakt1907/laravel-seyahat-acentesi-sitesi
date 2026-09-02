<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            if (!Schema::hasColumn('transfers', 'price_1_4')) {
                $table->decimal('price_1_4', 10, 2)->default(0)->after('price');
            }
            if (!Schema::hasColumn('transfers', 'price_5_6')) {
                $table->decimal('price_5_6', 10, 2)->default(0)->after('price_1_4');
            }
            if (!Schema::hasColumn('transfers', 'price_7_8')) {
                $table->decimal('price_7_8', 10, 2)->default(0)->after('price_5_6');
            }
            if (!Schema::hasColumn('transfers', 'price_9_14')) {
                $table->decimal('price_9_14', 10, 2)->default(0)->after('price_7_8');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn(['price_1_4', 'price_5_6', 'price_7_8', 'price_9_14']);
        });
    }
};
