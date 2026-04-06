<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ayarlar', function (Blueprint $table) {
            $table->text('section_order')->nullable()->after('notification_sound');
        });
    }

    public function down(): void
    {
        Schema::table('ayarlar', function (Blueprint $table) {
            $table->dropColumn('section_order');
        });
    }
};
