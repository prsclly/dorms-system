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
        Schema::create('students', function (Blueprint $table) {
            $table->id(); // Primary key

            // Foreign key ke residents.id
            $table->foreignId('resident_id')
                ->constrained('residents')
                ->onDelete('cascade');

            $table->string('nim')->unique(); // Ganti student_id jadi nim, tetap unik
            $table->string('name');
            $table->integer('total_point')->default(300); // Total poin default
            $table->timestamp('updated_at')->nullable(); // Waktu update terakhir
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
