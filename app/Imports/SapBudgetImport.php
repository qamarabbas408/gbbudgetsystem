<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class SapBudgetImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        // 1. Logic to ignore rows: 
        // If ADP NO is empty, or it's a summary row (contains *), skip it.
        if (empty($row['adp_no']) || $row['adp_no'] == '*' || empty($row['wbs_element'])) {
            return null;
        }

        // 2. Here you will define what to do with the valid data.
        // For now, let's just log it or prepare it for a database model.
        // Example: return new BudgetRecord([ ... ]);
        
        return null; // Change this once we create your database model
    }
}