<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('from_location', 100);
            $table->string('to_location', 100);
            $table->string('title', 200);
            $table->string('icon', 50)->default('fa-shuttle-van');
            $table->decimal('price', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->integer('sira')->default(0);
            $table->boolean('durum')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
