<?php

namespace App\Http\Controllers;

use App\Exports\PDExport;
use App\Models\PengajuanPerjalananDinas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $datas = PengajuanPerjalananDinas::find($request->id);
        $nama = explode(' / ', $datas->nama)[0];
        return Excel::download(new PDExport($datas), $nama . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
