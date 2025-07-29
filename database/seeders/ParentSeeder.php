<?php

namespace Database\Seeders;

use App\Models\Parents;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ParentSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {



    Parents::create(
      [
        'name' => 'testparent',
        'email' => 'parent@gmail.com',
        'password' => Hash::make('password123')
      ]
    );
}
}
