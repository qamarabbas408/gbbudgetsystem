<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProjectHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heads = [
            ['code' => 'A01270', 'description' => 'ERE-Pay Others'],
            ['code' => 'A02102', 'description' => 'Consultant based Feasibility Studies'],
            ['code' => 'A03970', 'description' => 'Operating Exp-Others'],
            ['code' => 'A09101', 'description' => 'Land Compensation'],
            ['code' => 'A09501', 'description' => 'Purchase of Transport'],
            ['code' => 'A09601', 'description' => 'Purchase of Plant & Machinery'],
            ['code' => 'A09701', 'description' => 'Purchase of Furniture & Fixture'],
            ['code' => 'A12102', 'description' => 'Roads'],
            ['code' => 'A12104', 'description' => 'Bridges'],
            ['code' => 'A12403', 'description' => 'Buildings'],
            ['code' => 'A12404', 'description' => 'Structures'],
        ];

        foreach ($heads as $head) {
            \App\Models\ProjectHead::updateOrCreate(['code' => $head['code']], $head);
        }
    }
}
