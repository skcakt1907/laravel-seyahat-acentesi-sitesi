<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('reviews', 'email')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->string('email', 150)->nullable()->after('name');
                $table->index(['email', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reviews', 'email')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropIndex(['email', 'created_at']);
                $table->dropColumn('email');
            });
        }
    }
};
