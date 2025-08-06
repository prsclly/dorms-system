<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan 'on_process' ke enum status
        DB::statement("ALTER TABLE permissions MODIFY status ENUM('pending', 'on_process', 'approved', 'rejected') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum awal (tanpa 'on_process')
        DB::statement("ALTER TABLE permissions MODIFY status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
