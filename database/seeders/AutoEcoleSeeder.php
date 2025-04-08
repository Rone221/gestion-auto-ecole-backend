<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolManagement\AutoEcole;

class AutoEcoleSeeder extends Seeder
{
    public function run(): void
    {
        AutoEcole::factory(1)->create();
    }
}
