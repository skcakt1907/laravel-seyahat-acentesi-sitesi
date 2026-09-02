<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Icerik tablolarina cok dilli metin icin `ceviri` JSON kolonu ekler.
 * Ana kolonlar Ingilizce (kaynak dil) kalir; ceviri->{dil}->{alan} ile ustune yazilir.
 */
return new class extends Migration
{
    private array $tablolar = ['transfers', 'activities', 'slider', 'blog', 'sayfalar'];

    public function up(): void
    {
        foreach ($this->tablolar as $t) {
            if (Schema::hasTable($t) && ! Schema::hasColumn($t, 'ceviri')) {
                DB::statement("ALTER TABLE `{$t}` ADD COLUMN `ceviri` JSON NULL");
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tablolar as $t) {
            if (Schema::hasTable($t) && Schema::hasColumn($t, 'ceviri')) {
                DB::statement("ALTER TABLE `{$t}` DROP COLUMN `ceviri`");
            }
        }
    }
};
