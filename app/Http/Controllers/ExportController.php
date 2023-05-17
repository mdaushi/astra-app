<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\PDExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PengajuanPerjalananDinas;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $pengajuan = PengajuanPerjalananDinas::find($request->id);
        $approval1 = User::where('email', $pengajuan->pegawai->approval1)->first();
        $approval2 = User::where('email', $pengajuan->pegawai->approval2)->first();
        $approval3 = User::where('email', $pengajuan->pegawai->approval3)->first();

        $sign1 = '';
        $sign2 = '';
        $sign3 = '';
        $singuser = base64_encode(QrCode::generate('dokument ini terverifikasi secara resmi di astra app. dibuat oleh ' . $pengajuan->pegawai->nama . ' pada tanggal ' . Carbon::parse($pengajuan->created_at)->format('d M Y')));

        if($pengajuan->sign_chief_at){
            $sign1 = base64_encode(QrCode::generate($this->scanQRCodeResult($pengajuan->nama_chief_signed, $pengajuan->sign_chief_at)));
        }
        if($pengajuan->sign_hrd_at){
            $sign2 = base64_encode(QrCode::generate($this->scanQRCodeResult($pengajuan->nama_hrd_signed, $pengajuan->sign_hrd_at)));
        }
        if($pengajuan->sign_ga_at){
            $sign3 = base64_encode(QrCode::generate($this->scanQRCodeResult($pengajuan->nama_ga_signed, $pengajuan->sign_ga_at)));
        }

        // return $approval1;
        $pdf = Pdf::loadView('export.print', 
        [
            'datas' => $pengajuan, 
            'approval1' => $approval1, 
            'approval2' => $approval2, 
            'approval3' => $approval3,
            'signuser' => $singuser,
            'sign1' => $sign1,
            'sign2' => $sign2,
            'sign3' => $sign3,
        ])->setPaper('a5', 'landscape');
        
        return $pdf->download($pengajuan->pegawai->nama . '-' . $pengajuan->created_at . '.pdf');
    }

    private function scanQRCodeResult($signer, $date)
    {
        return 'dokument ini terverifikasi secara resmi di astra app. di tanda tangani oleh ' . $signer . ' pada tanggal ' . Carbon::parse($date)->format('d M Y');        
    }
}
