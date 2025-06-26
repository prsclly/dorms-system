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
      Schema::create('feedback', function (Blueprint $table) {
        $table->id();
        $table->date('date'); // tanggal komplain
        $table->enum('category', ['Hygiene', 'Food Quality', 'Taste', 'Others']);
        $table->text('message');
        $table->unsignedBigInteger('resident_id');
        $table->unsignedBigInteger('meal_id');
        $table->timestamps();

        $table->foreign('resident_id')->references('id')->on('residents')->onDelete('cascade');
        $table->foreign('meal_id')->references('id')->on('meals')->onDelete('cascade');
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }

};
