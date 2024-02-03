<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->integer('data_id')->nullable();
            $table->string('sort')->nullable();
            $table->string('user_created')->nullable();
            $table->string('user_updated')->nullable();
            $table->string('date_created')->nullable();
            $table->string('date_updated')->nullable();
            $table->string('HEX')->nullable();
            $table->string('temperature')->nullable();
            $table->integer('co2')->nullable();
            $table->integer('c2ho')->nullable();
            $table->string('humidity')->nullable();
            $table->integer('check')->nullable();
            $table->integer('pm10')->nullable();
            $table->integer('pm25')->nullable();
            $table->integer('tvoc')->nullable();
            $table->boolean('valid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data');
    }
};
