<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Roles::firstOrCreate(['name' => 'Developer']);
        Roles::firstOrCreate(['name' => 'Project Manager']);
        Roles::firstOrCreate(['name' => 'Tester']);
    }
}
