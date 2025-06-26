<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\Resident;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada minimal 1 resident dulu
        $resident = Resident::first() ?? Resident::factory()->create();

        Report::create([
            'resident_id' => $resident->id,
            'category' => 'Electricity',
            'description' => 'Power outage in the living room.',
            'photo' => null,
            'submitted_at' => now(),
        ]);

        Report::create([
            'resident_id' => $resident->id,
            'category' => 'AC',
            'description' => 'AC not working properly.',
            'photo' => null,
            'submitted_at' => now()->subDays(1),
        ]);

        Report::create([
            'resident_id' => $resident->id,
            'category' => 'Plumbing',
            'description' => 'Leaking pipe under kitchen sink.',
            'photo' => null,
            'submitted_at' => now()->subDays(2),
        ]);
    }
}
