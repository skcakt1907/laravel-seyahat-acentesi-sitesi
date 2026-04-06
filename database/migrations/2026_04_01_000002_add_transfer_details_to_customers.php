<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('hotel_name', 200)->nullable()->after('activity_name');
            $table->integer('adult_count')->nullable()->after('hotel_name');
            $table->text('adult_names')->nullable()->after('adult_count');
            $table->integer('child_count')->nullable()->after('adult_names');
            $table->text('child_names')->nullable()->after('child_count');
            $table->text('notes')->nullable()->after('child_names');
            $table->date('arrival_date')->nullable()->after('notes');
            $table->string('arrival_time', 10)->nullable()->after('arrival_date');
            $table->string('arrival_flight', 30)->nullable()->after('arrival_time');
            $table->date('departure_date')->nullable()->after('arrival_flight');
            $table->string('departure_time', 10)->nullable()->after('departure_date');
            $table->string('departure_flight', 30)->nullable()->after('departure_time');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'hotel_name', 'adult_count', 'adult_names', 'child_count', 'child_names',
                'notes', 'arrival_date', 'arrival_time', 'arrival_flight',
                'departure_date', 'departure_time', 'departure_flight',
            ]);
        });
    }
};
