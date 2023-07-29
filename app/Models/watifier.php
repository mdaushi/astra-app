<?php

namespace App\Models;

use App\Http\Integrations\watifier\Requests\CheckSessionRequest;
use App\Http\Integrations\watifier\Requests\GetQrcodeRequest;
use App\Http\Integrations\watifier\Requests\InitInstanceRequest;
use App\Http\Integrations\watifier\Requests\SendMessageRequest;
use App\Http\Integrations\watifier\WatifierConnector;
use IbrahimBedir\FilamentDynamicSettingsPage\Models\Setting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class watifier extends Model
{
    use HasFactory;

    protected $guarded = [];

    static function getAccess() {
        return Setting::whereIn('key', ['watifier_device', 'watifier_token']);
    }

    static function getSession() {
        return Setting::where('key', 'watifier_device');
    }

    static function statusSession(){
        $watifier_key = env("WATIFIER_KEY");

        $connector = new WatifierConnector();
        $requestStatus = new CheckSessionRequest($watifier_key);

        $response = $connector->send($requestStatus);
        // dd($response->json());

        return $response->json();
    }

    static function getQrcode()
    {
        $watifier_key = env("WATIFIER_KEY");

        $connector = new WatifierConnector();
        $requestQrcode = new GetQrcodeRequest($watifier_key);

        $response = $connector->send($requestQrcode);

        return $response->json();
    }

    static function initInstanceQrcode()
    {
        $watifier_key = env("WATIFIER_KEY");

        $connector = new WatifierConnector();
        $requestInstance = new InitInstanceRequest($watifier_key);

        $response = $connector->send($requestInstance);

        return $response->json(); 
    }

    static function sendMessage(array $payload)
    {
        $watifier_key = env("WATIFIER_KEY");

        $connector = new WatifierConnector();
        $sendMessage = new SendMessageRequest(key: $watifier_key, payload: $payload);

        $response = $connector->send($sendMessage);

        return $response->json(); 
    }


}
