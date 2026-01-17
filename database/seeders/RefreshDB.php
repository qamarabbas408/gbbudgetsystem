<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class RefreshDB extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // 1. Disable foreign key constraints
        Schema::disableForeignKeyConstraints();

        // 2. Truncate (empty) the tables
        // Order matters if you aren't using disableForeignKeyConstraints, 
        // but with it disabled, you can clear them in any order.
        DB::table('sap_dumps')->truncate();
        DB::table('sap_uploads')->truncate();
        DB::table('project_heads')->truncate();
        DB::table('sectors')->truncate();
        DB::table('departments')->truncate();

        // 3. Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();

        $this->command->info('SAP tables cleared successfully!');   
    }
}
