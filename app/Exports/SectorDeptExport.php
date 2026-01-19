<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class SectorDeptExport implements FromView, ShouldAutoSize
{
    protected $sectors;

    protected $date;

    protected $unit;

    public function __construct($sectors, $date,$unit)
    {
        $this->sectors = $sectors;
        $this->date = $date;
        $this->unit = $unit;
    }

    public function view(): View
    {
        return view('exports.sector_dept_excel', [
            'sectors' => $this->sectors,
            'date' => $this->date,
            'unit' => $this->unit,

        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // This forces Excel to treat the sheet as UTF-8
                $event->sheet->getDelegate()->getParent()->getProperties()->setCustomProperty('Charset', 'UTF-8');
            },
        ];
    }
}
