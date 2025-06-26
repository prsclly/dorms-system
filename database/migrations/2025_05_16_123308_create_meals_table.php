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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('meal_type', ['Breakfast', 'Lunch', 'Dinner']);
            $table->string('time'); // contoh: '07.00 - 07.30'
            $table->string('menu_description')->nullable();
            $table->unsignedBigInteger('pic_id');
            $table->timestamps();

            // Foreign key ke tabel pics
            $table->foreign('pic_id')->references('id')->on('pics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
