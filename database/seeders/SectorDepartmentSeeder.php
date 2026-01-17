<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SectorDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'A&LE Sector' => [
                ['name' => 'Excise and Taxation & Zakat Ushr Deptt.', 'abbreviation' => 'EXT'],
                ['name' => 'Home and Police', 'abbreviation' => 'HNP'],
                ['name' => 'Information Department', 'abbreviation' => 'INFO'],
                ['name' => 'Law Department', 'abbreviation' => 'LAW'],
                ['name' => 'S&GAD Department', 'abbreviation' => 'S&GAD'],
            ],
            'Energy Sector' => [
                ['name' => 'Power', 'abbreviation' => 'Pow'],
            ],
            'NRM - Natural Resource and Minerals Sector' => [
                ['name' => 'Agriculture, Animal Husbandry & Fisheries', 'abbreviation' => 'ALF'],
                ['name' => 'Forestry, Wild life & Environment', 'abbreviation' => 'FST'],
                ['name' => 'Minerals, Mines, Industries & Commerce', 'abbreviation' => 'MILC'],
                ['name' => 'Food Department', 'abbreviation' => 'FD'],
                ['name' => 'Tourism, Culture, Sports, Archaeology & Museums', 'abbreviation' => 'TCSM'],
                ['name' => 'Water Management & Irrigation', 'abbreviation' => 'WM'],
            ],
            'Social Sector' => [
                ['name' => 'Social Welfare, Women Dev. Human & Child Rights and Youth Affairs Department', 'abbreviation' => 'SW'],
                ['name' => 'Information Technolgy', 'abbreviation' => 'IT'],
                ['name' => 'Higher Technical and Special School Education', 'abbreviation' => 'HTE'],
                ['name' => 'School Education', 'abbreviation' => 'EDU'],
                ['name' => 'Health', 'abbreviation' => 'HLT'],
            ],
            'AR&UD Sector' => [
                ['name' => 'Planning and Development', 'abbreviation' => 'PND'],
                ['name' => 'LG&RD', 'abbreviation' => 'LGRD'],
                ['name' => 'Gilgit Development Authority', 'abbreviation' => 'GDA'],
                ['name' => 'Skurdu Development Authority', 'abbreviation' => 'SDA'],
                ['name' => 'CDA', 'abbreviation' => 'CDA'],
                ['name' => 'Others', 'abbreviation' => 'OTH'],
            ],
            'Communication & Works Sector' => [
                ['name' => 'Physical Planning & Housing', 'abbreviation' => 'PPH'],
                ['name' => 'Transport & Communication', 'abbreviation' => 'T&C'],
            ],
        ];

        foreach ($data as $sectorName => $departments) {
            $sector = \App\Models\Sector::create(['name' => $sectorName]);
            foreach ($departments as $dept) {
                $sector->departments()->create($dept);
            }
        }
    }
}
