<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\DepartmentMapping;
use Illuminate\Support\Facades\DB;

class MappingRulesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear existing mappings to start fresh
        DB::table('department_mappings')->truncate();

        $rules = [
            // --- ADP MAPPINGS ---
            ['type' => 'ADP', 'dept' => 'Health', 'start' => 'A0778', 'end' => 'A0989'],
            ['type' => 'ADP', 'dept' => 'ALF', 'start' => 'A0183', 'end' => 'A0269'],
            ['type' => 'ADP', 'dept' => 'CDA', 'start' => 'A1231', 'end' => 'A1233'],
            ['type' => 'ADP', 'dept' => 'EDU', 'start' => 'A0540', 'end' => 'A0777'],
            ['type' => 'ADP', 'dept' => 'EXT', 'start' => 'A0001', 'end' => 'A0008'],
            ['type' => 'ADP', 'dept' => 'FD', 'start' => 'A0320', 'end' => 'A0338'],
            ['type' => 'ADP', 'dept' => 'ALF', 'start' => 'A0270', 'end' => 'A0297'],
            ['type' => 'ADP', 'dept' => 'GDA', 'start' => 'A1179', 'end' => 'A1202'],
            ['type' => 'ADP', 'dept' => 'HTE', 'start' => 'A0485', 'end' => 'A0539'],
            ['type' => 'ADP', 'dept' => 'INFO', 'start' => 'A0057', 'end' => 'A0061'],
            ['type' => 'ADP', 'dept' => 'WM', 'start' => 'A0378', 'end' => 'A0427'],
            ['type' => 'ADP', 'dept' => 'IT', 'start' => 'A0475', 'end' => 'A0484'],
            ['type' => 'ADP', 'dept' => 'LAW', 'start' => 'A0062', 'end' => 'A0084'],
            ['type' => 'ADP', 'dept' => 'LGRD', 'start' => 'A1004', 'end' => 'A1178'],
            ['type' => 'ADP', 'dept' => 'MILC', 'start' => 'A0298', 'end' => 'A0319'],
            ['type' => 'ADP', 'dept' => 'PND', 'start' => 'A0990', 'end' => 'A1003'],
            ['type' => 'ADP', 'dept' => 'PPH', 'start' => 'A1245', 'end' => 'A1421'],
            ['type' => 'ADP', 'dept' => 'S&GAD', 'start' => 'A0085', 'end' => 'A0095'],
            ['type' => 'ADP', 'dept' => 'SDA', 'start' => 'A1203', 'end' => 'A1230'],
            ['type' => 'ADP', 'dept' => 'SW', 'start' => 'A0428', 'end' => 'A0474'],
            ['type' => 'ADP', 'dept' => 'T&C', 'start' => 'A0339', 'end' => 'A0386'],
            ['type' => 'ADP', 'dept' => 'EXT', 'start' => 'A1234', 'end' => 'A1244'],
            ['type' => 'ADP', 'dept' => 'HNP', 'start' => 'A0009', 'end' => 'A0056'],
            ['type' => 'ADP', 'dept' => 'Pow', 'start' => 'A0096', 'end' => 'A0182'],
            ['type' => 'ADP', 'dept' => 'FST', 'start' => 'A0270', 'end' => 'A0297'],
            ['type' => 'ADP', 'dept' => 'TCSM', 'start' => 'A0339', 'end' => 'A0386'],
            ['type' => 'ADP', 'dept' => 'OTH', 'start' => 'A1234', 'end' => 'A1244'],

            // --- SDG MAPPINGS ---
            ['type' => 'SDG', 'dept' => 'ALF', 'start' => 'SG015', 'end' => 'SG038'],
            ['type' => 'SDG', 'dept' => 'EDU', 'start' => 'SG111', 'end' => 'SG165'],
            ['type' => 'SDG', 'dept' => 'HTE', 'start' => 'SG108', 'end' => 'SG110'],
            ['type' => 'SDG', 'dept' => 'FD', 'start' => 'SG039', 'end' => 'SG042'],
            ['type' => 'SDG', 'dept' => 'INFO', 'start' => 'SG001', 'end' => 'SG001'],
            ['type' => 'SDG', 'dept' => 'WM', 'start' => 'SG047', 'end' => 'SG100'],
            ['type' => 'SDG', 'dept' => 'IT', 'start' => 'SG103', 'end' => 'SG107'],
            ['type' => 'SDG', 'dept' => 'HLT', 'start' => 'SG166', 'end' => 'SG189'],
            ['type' => 'SDG', 'dept' => 'Pow', 'start' => 'SG002', 'end' => 'SG014'],
            ['type' => 'SDG', 'dept' => 'SW', 'start' => 'SG101', 'end' => 'SG101'],
            ['type' => 'SDG', 'dept' => 'LGRD', 'start' => 'SG190', 'end' => 'SG491'],
            ['type' => 'SDG', 'dept' => 'SDA', 'start' => 'SG492', 'end' => 'SG494'],
            ['type' => 'SDG', 'dept' => 'PPH', 'start' => 'SG495', 'end' => 'SG524'],
            ['type' => 'SDG', 'dept' => 'T&C', 'start' => 'SG525', 'end' => 'SG631'],
            ['type' => 'SDG', 'dept' => 'T&C', 'start' => 'SG043', 'end' => 'SG046'],
        ];

        foreach ($rules as $rule) {
            // THE FIX: Search by Name OR Abbreviation
            $department = Department::where('name', $rule['dept'])
                                    ->orWhere('abbreviation', $rule['dept'])
                                    ->first();

            if ($department) {
                DepartmentMapping::create([
                    'department_id' => $department->id,
                    'start_adp'     => strtoupper($rule['start']),
                    'end_adp'       => strtoupper($rule['end']),
                    'type'          => $rule['type'],
                ]);
            } else {
                $this->command->error("Department lookup failed for: {$rule['dept']}");
            }
        }
    }
}