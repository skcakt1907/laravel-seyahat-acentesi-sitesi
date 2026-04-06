<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ayarlar', function (Blueprint $table) {
            $table->string('notification_sound')->nullable()->after('odeme_ayarlari');
        });
    }

    public function down(): void
    {
        Schema::table('ayarlar', function (Blueprint $table) {
            $table->dropColumn('notification_sound');
        });
    }
};
