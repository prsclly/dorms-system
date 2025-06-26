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
        Schema::create('feedback_reports', function (Blueprint $table) {
            $table->id();

            // Relasi ke laporan dan penghuni
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->foreignId('resident_id')->constrained('residents')->onDelete('cascade');

            // Komentar atau isi feedback
            $table->text('comment')->nullable();

            // Tanggal submit + timestamps biasa
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_report');
    }
};
