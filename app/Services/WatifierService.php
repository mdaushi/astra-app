<?php

namespace App\Services;

use IbrahimBedir\FilamentDynamicSettingsPage\Models\Setting;

class WatifierService
{
    private static function replaceString(string $from, string $to, string $string): string
    {
        return str_replace($from, $to, $string);
    }

    public static function requestApprovalMessage(string $approved_by, string $pegawai, string $link_pengajuan): string
    {
        $text = Setting::where('key', 'request.approval.messages')->value('value');

        $text = self::replaceString(from: "{{approved_by}}", to: $approved_by, string: $text);
        $text = self::replaceString(from: "{{pegawai}}", to: $pegawai, string: $text);
        $text = self::replaceString(from: "{{link_pengajuan}}", to: $link_pengajuan, string: $text);

        return $text;
    }

    public static function processApprovalMessage(string $approved_by, string $tanggal_pengajuan): string
    {
        $text = Setting::where('key', 'process.approval.pengajuan')->value('value');

        $text = self::replaceString(from: "{{approved_by}}", to: $approved_by, string: $text);
        $text = self::replaceString(from: "{{tanggal_pengajuan}}", to: $tanggal_pengajuan, string: $text);

        return $text;
    }

    public static function rejectedApprovalMessage(string $pegawai, string $tanggal_pengajuan): string
    {
        $text = Setting::where('key', 'rejected.approval.message')->value('value');

        $text = self::replaceString(from: "{{pegawai}}", to: $pegawai, string: $text);
        $text = self::replaceString(from: "{{tanggal_pengajuan}}", to: $tanggal_pengajuan, string: $text);

        return $text;
    }

    public static function layananOtherMessage(string $pegawai, string $alasan): string
    {
        $text = Setting::where('key', 'layanan.other.message')->value('value');

        $text = self::replaceString(from: "{{pegawai}}", to: $pegawai, string: $text);
        $text = self::replaceString(from: "{{alasan_layanan_other}}", to: $alasan, string: $text);

        return $text;
    }

    public static function PdBersamaMessage(string $pegawai, string $pegawai_pd_bersama): string
    {
        $text = Setting::where('key', 'pd.bersama.message')->value('value');

        $text = Setting::replaceString(from: "{{pegawai}}", to: $pegawai, string: $text);
        $text = Setting::replaceString(from: "{{pegawai_pd_bersama}}", to: $pegawai_pd_bersama, string: $text);

        return $text;
    }
}