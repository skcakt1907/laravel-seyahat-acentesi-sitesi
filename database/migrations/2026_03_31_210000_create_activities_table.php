<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('gallery')->nullable()->comment('JSON array of image filenames');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('badge', 50)->nullable()->default('Popular');
            $table->integer('sira')->default(0);
            $table->boolean('durum')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
