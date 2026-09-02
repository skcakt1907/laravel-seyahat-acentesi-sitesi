<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ayarlar', function (Blueprint $table) {
            $table->string('about_title', 255)->nullable()->after('site_desc');
            $table->text('about_text')->nullable()->after('about_title');
            $table->text('about_mission')->nullable()->after('about_text');
            $table->string('about_image', 255)->nullable()->after('about_mission');
            $table->text('about_stats')->nullable()->after('about_image'); // JSON
            $table->text('about_features')->nullable()->after('about_stats'); // JSON
        });
    }

    public function down(): void
    {
        Schema::table('ayarlar', function (Blueprint $table) {
            $table->dropColumn(['about_title', 'about_text', 'about_mission', 'about_image', 'about_stats', 'about_features']);
        });
    }
};
