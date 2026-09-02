<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Odemesi tamamlanmamis rezervasyonlar artik `customers` tablosuna hic yazilmaz.
     * Form verisi odeme bitene kadar burada bekler; odeme onaylaninca musteri olusur.
     */
    public function up(): void
    {
        if (!Schema::hasTable('pending_bookings')) {
            Schema::create('pending_bookings', function (Blueprint $table) {
                $table->id();
                $table->json('data');              // dogrulanmis form verisi
                $table->string('email', 191)->nullable()->index();
                $table->string('type', 20)->default('activity');
                $table->timestamps();
                $table->index('created_at');
            });
        }

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'pending_id')) {
                $table->unsignedBigInteger('pending_id')->nullable()->after('customer_id')->index();
            }
        });

        // Odeme onaylanana kadar musteri kaydi olmadigi icin customer_id bos olabilmeli
        if (Schema::hasColumn('payments', 'customer_id')) {
            \DB::statement('ALTER TABLE `payments` MODIFY `customer_id` INT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_bookings');
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'pending_id')) {
                $table->dropColumn('pending_id');
            }
        });
    }
};
