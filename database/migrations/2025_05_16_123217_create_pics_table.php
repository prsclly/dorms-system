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
        Schema::create('pics', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama PIC, contoh: Bu Sri, Pak Sigit
            $table->string('email')->unique()->nullable(); // Opsional, kalau ingin ada login
            $table->string('phone')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status kolom baru
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pics');
    }
};
