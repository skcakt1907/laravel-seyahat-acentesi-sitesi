<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Musterinin rezervasyon yaptigi dil. Onay e-postasi bu dilde gonderilir.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customers') && ! Schema::hasColumn('customers', 'dil')) {
            DB::statement("ALTER TABLE `customers` ADD COLUMN `dil` VARCHAR(5) NOT NULL DEFAULT 'en'");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('customers') && Schema::hasColumn('customers', 'dil')) {
            DB::statement("ALTER TABLE `customers` DROP COLUMN `dil`");
        }
    }
};
