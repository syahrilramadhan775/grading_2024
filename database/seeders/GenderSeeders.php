<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenderSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gender::firstOrCreate(['name' => "Female"]);
        Gender::firstOrCreate(['name' => "Male"]);
        Gender::firstOrCreate(['name' => "Agender"]);
        Gender::firstOrCreate(['name' => "Transgender"]);
    }
}
