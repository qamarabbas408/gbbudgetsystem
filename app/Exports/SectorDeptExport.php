<?php 
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class SectorDeptExport implements FromView, ShouldAutoSize
{
    protected $sectors;
    protected $date;

    public function __construct($sectors, $date)
    {
        $this->sectors = $sectors;
        $this->date = $date;
    }

    public function view(): View
    {
        return view('exports.sector_dept_excel', [
            'sectors' => $this->sectors,
            'date' => $this->date
        ]);
    }

     public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // This forces Excel to treat the sheet as UTF-8
                $event->sheet->getDelegate()->getParent()->getProperties()->setCustomProperty('Charset', 'UTF-8');
            },
        ];
    }
}