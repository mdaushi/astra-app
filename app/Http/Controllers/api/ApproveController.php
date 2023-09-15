<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\Pegawai;
use App\Models\watifier;
use Illuminate\Http\Request;
use App\Events\AgreedPengajuan;
use App\Events\ApprovalProcessed;
use App\Events\ApprovedProcessed;
use App\Events\PDBarengProcessed;
use App\Events\RejectedProcessed;
use App\Http\Controllers\Controller;
use App\Models\PengajuanPerjalananDinas;

class ApproveController extends Controller
{
    public function create(Request $request)
    {
        // 62xxxxxxx@s.whatsapp.net
        $remoteJid = $request->remoteJid;
        $inputText = $request->inputText;
        $idPengajuan = $request->uuid;

        $nomorExplode = explode('@', $remoteJid);
        $phone = $nomorExplode[0];
        $prefix = substr($phone, 0, 2);
        if($prefix == '62')
        {
            $phone = substr_replace($phone, '0', 0, 2);
        }
        switch ($inputText) {
            case 'terima':
                $this->approve($phone, $idPengajuan);
                break;
            case 'tolak': 
                $this->reject($idPengajuan);
                break;
            default:
                return watifier::sendMessage([
                    'id' => $remoteJid, 
                    'message' => 'Maaf, perintah tidak dikenali. reply pesan dengan kata "terima" untuk mengapprove pengajuan atau kata "tolak" untuk reject pengajuan '
                ]);
                break;
        }
    }

    private function approve($remoteJid, $idPengajuan)
    {
        try {
            $pengajuan = PengajuanPerjalananDinas::find($idPengajuan);
            $pegawaiApproval = Pegawai::where('whatsapp', $remoteJid)->first();
            $rolesApproval = $pegawaiApproval->user->getRoleNames()->toArray();
            
            $pengajuan->processApprove($pegawaiApproval, $rolesApproval);

            // send mail approval
            ApprovalProcessed::dispatch($pengajuan);
                
            // send notif to user
            ApprovedProcessed::dispatch($pengajuan, $pegawaiApproval);
            
            // send notif pd bersama
            PDBarengProcessed::dispatch($pengajuan);
        
            AgreedPengajuan::dispatch($pengajuan);
            
            return response()->json(['status' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'failed']);
        }
        
    }

    private function reject($idPengajuan){
        $pengajuan = PengajuanPerjalananDinas::find($idPengajuan);
        $pengajuan->update(['rejected_at' => Carbon::now()]);

        RejectedProcessed::dispatch($pengajuan);

        return response()->json(['status' => 'success']);
    }
}
