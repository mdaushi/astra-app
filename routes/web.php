<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pegawai;
use BaconQrCode\Writer;
use App\Models\watifier;
use App\Exports\PDExport;
use App\Models\Ekspedisi;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use BaconQrCode\Renderer\ImageRenderer;
use App\Models\PengajuanPerjalananDinas;
use App\Http\Controllers\ExportController;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use App\Http\Integrations\watifier\WatifierConnector;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use IbrahimBedir\FilamentDynamicSettingsPage\Models\Setting;
use App\Http\Integrations\watifier\Requests\SendMessageRequest;
use App\Http\Integrations\watifier\Requests\CheckSessionRequest;
use App\Services\WatifierService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/export', [ExportController::class, 'export'])->name('export');

Route::get('/testing', function(){
    // $token =  Setting::where('key', 'watifier_token')->first()->value;
    // $session =  Setting::where('key', 'watifier_device')->first()->value;

    // $connector = new WatifierConnector($token);
    // $status = new CheckSessionRequest($session);
    
    // $response = $connector->send($status);

    // dd($response);
    // return response()->json($response->json());

    // $watifier_key = env("WATIFIER_KEY");

    // $connector = new WatifierConnector();
    // $sendMessage = new SendMessageRequest(key: $watifier_key, payload: ['id' => "6281952905756", 'message' => 'testing 1']);

    // $response = $connector->send($sendMessage);

    // return $response->json();

    
    // $user = User::with('pegawai')->find(37);
    // return $user->pegawai->whatsapp;
    // return watifier::sendMessage(['id' => '6281527754876', 'message' => WatifierService::requestApprovalMessage(approved_by: "Firdaus", pegawai: "daus", link_pengajuan: "www.google.com")]);
    // return WatifierService::requestApprovalMessage(approved_by: "Firdaus", pegawai: "daus", link_pengajuan: "www.google.com");

    // return watifier::getSession();
    // return Pegawai::with('jabatan')->get();
    // return PengajuanPerjalananDinas::with('pegawai.approvalsatu.pegawai', 'pegawai.approvaldua.pegawai', 'pegawai.approvaltiga.pegawai')->find(77);

    $pegawai = Pegawai::with('jabatan')->find(42);
    return $pegawai;
});