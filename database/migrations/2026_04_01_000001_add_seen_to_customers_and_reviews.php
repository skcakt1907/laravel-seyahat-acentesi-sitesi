<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('seen')->default(0)->after('activity_name');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('seen')->default(0)->after('approved');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('seen');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('seen');
        });
    }
};
