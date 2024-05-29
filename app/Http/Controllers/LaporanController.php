<?php

namespace App\Http\Controllers;

use App\Exports\ItemRuanganExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        return view('laporan.item-ruangan.index');
    }

    public function create()
    {

    }
    public function export()
    {
        return Excel::download(new MutasiExport, 'Laporan Mutasi Item Ke Ruangan.xlsx');
    }
    public function excel_mutasi(Request $request)
    {
        $tgl_mulai = $request->input('tgl_mulai') . ' 00:00:00';
        $tgl_akhir = $request->input('tgl_akhir') . ' 23:59:59';
        $nama_file = 'laporan Mutasi Item Ke ruangan ' . $tgl_mulai . ' - Hingga - ' . $tgl_akhir . '.xlsx';
        return Excel::download(new MutasiExport($tgl_mulai, $tgl_akhir), $nama_file);
    }
    public function excel_item(Request $request)
    {
        $unit = $request->input('unit');
        $nama_file = 'laporan Item ruangan ' . $unit . '.xlsx';
        return Excel::download(new ItemRuanganExport($unit), $nama_file);
    }
}
