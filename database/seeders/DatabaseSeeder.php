<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
     public function run(): void
    {
        $this->call([
            RefreshDB::class,
            ProjectHeadSeeder::class, // Run this first
            // SectorSeeder::class,      // Run this first
            SectorDepartmentSeeder::class,  // Run this second (depends on sectors)
            MappingRulesSeeder::class,
        ]);
    }
}
