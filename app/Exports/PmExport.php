<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use DB;

class PmExport implements FromView, WithEvents, WithStyles
{
    use Exportable;
    public function __construct(string $tgl_mulai, string $tgl_akhir)
    {
        $this->tgl_mulai = $tgl_mulai;
        $this->tgl_akhir = $tgl_akhir;
        // dd($tgl_akhir);
    }
    public function view(): View
    {
        // $test = DB::connection("mysql")
        //         ->table('data_inventaris')
        //         ->join('maintanance', 'maintanance.kode_item', '=', 'data_inventaris.kode_item')
        //         ->where('maintanance.created_at', '>=', $this->tgl_mulai)
        //         ->where('maintanance.created_at', '<=', $this->tgl_akhir)
        //         ->get();
        //         dd($test);
        return view('excel.excel_pm', [
            'pm' => DB::connection("mysql")
                ->table('data_inventaris')
                ->join('maintanance', 'maintanance.kode_item', '=', 'data_inventaris.kode_item')
                ->where('maintanance.created_at', '>=', $this->tgl_mulai)
                ->where('maintanance.created_at', '<=', $this->tgl_akhir)
                ->get()
        ]);
    }
    public function registerEvents(): array
    {

        return [

            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = $event->sheet->getDelegate()->getHighestRow();

                $cellRange = 'A4:O' . $lastRow;
                $event->sheet->getDelegate()->getStyle('A4:E4')->getFont()->setName('Times New Roman')->setBold(true)->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B2')->getFont()->setBold(true);
    }
}
