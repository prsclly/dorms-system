<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah enum field status di table tasks
        DB::statement("ALTER TABLE tasks MODIFY status ENUM('Pending', 'Assigned', 'In Progress', 'Completed', 'Rejected') DEFAULT 'Pending'");
    }

    public function down(): void
    {
        // Rollback ke enum awal tanpa 'Rejected'
        DB::statement("ALTER TABLE tasks MODIFY status ENUM('Pending', 'Assigned', 'In Progress', 'Completed') DEFAULT 'Pending'");
    }
};
