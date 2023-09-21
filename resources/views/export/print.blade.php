<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PD {{ $datas->pegawai->nama }} - {{ $datas->created_at }}</title>
    <style type="text/css" media="all">
        * {
            font-size: 11px;
            margin: 2px 2px;
        }

        table {
            border: 1px solid;
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding-left: 15px;
            padding-right: 15px;
            padding-top: 2px;
            padding-bottom: 2px;
            text-align: left;
        }

        .table-header td {
            background-color: #D9D9D9
        }

        .text-center {
            text-align: center;
        }

        .uppercase {
            text-transform: uppercase
        }

        .capitalize {
            text-transform: capitalize
        }

        .bold {
            font-weight: bold
        }

        .no-border {
            border: none
        }
    </style>
</head>

<body>

    {{-- indentity --}}
    <table class="no-border">
        <tr>
            <td>
                <p>PT ASTRA INTERNATIONAL Tbk - HONDA</p>
            </td>
            <td>
                <img style="float:right;width:150px" src="{{ public_path('/img/Gambar1.png') }}" alt="">
            </td>
        </tr>
    </table>

    {{-- nomor surat --}}
    <p class="uppercase bold text-center" style="margin-top: 20px; font-size: 15px">formulis perjalanan dinas</p>
    <p class="uppercase bold text-center" style="font-size: 15px;margin-bottom: 10px">{{ $format_surat }}</p>
    {{-- informasi head --}}
    <div style="overflow-x:auto;">
        <p>Yang melakukan perjalanan dinas</p>
        <table>
            <tr>
                <td style="width: 10px">Nama/NPK</td>
                <td style="width: 2px">:</td>
                <td style="width: 40%">{{ $datas->nama }}</td>
                <td style="border-left: 1px solid">Prepayment</td>
                <td>Rp -</td>
            </tr>
            <tr>
                <td>Golongan</td>
                <td>:</td>
                <td>{{ $datas->pegawai->golongan->nama }}</td>
                <td style="border-left: 1px solid">Pembayaran Prep</td>
                <td></td>
            </tr>
            <tr>
                <td>Penginapan</td>
                <td>:</td>
                <td class="capitalize">{{ $datas->penginapan }}</td>
                <td style="border-left: 1px solid"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nama Bank :</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="border-left: 1px solid"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No. Rekening :</td>
                <td></td>
            </tr>
        </table>
    </div>
    {{-- kegiatan --}}
    <div style="overflow-x:auto;">
        <p class="uppercase bold">Kegiatan & tiket</p>
        <table border="1">
            {{-- header --}}
            <tr class="table-header">
                <td class="text-center" rowspan="2">Tanggal</td>
                <td class="text-center" colspan="2">Kota Tujuan</td>
                <td class="text-center" rowspan="2">Kegiatan Pokok (Ringkas & Jelas)</td>
                <td class="text-center" colspan="2">Jam</td>
                <td class="text-center" rowspan="2">Maskapai / <br> No.Penerbangan</td>
                <td class="text-center" rowspan="2">Beli Sendiri / <br> Via GA</td>
            </tr>
            <tr class="table-header">
                <td class="text-center">Dari</td>
                <td class="text-center">Ke</td>
                <td class="text-center">Berangkat</td>
                <td class="text-center">Tiba</td>
            </tr>

            {{-- item kegiatan --}}

            @for ($i = 0; $i < count($datas->kegiatan); $i++)
                <tr>
                    <td style="height: 15px">{{ $datas->kegiatan[$i]->tanggal ?? ' ' }}</td>
                    <td class="capitalize">{{ $datas->kegiatan[$i]->dari_kota ?? ' ' }}</td>
                    <td class="capitalize">{{ $datas->kegiatan[$i]->ke_kota ?? ' ' }}</td>
                    <td class="capitalize">{{ $datas->kegiatan[$i]->kegiatan_pokok ?? ' ' }}</td>
                    <td>{{ $datas->kegiatan[$i]->berangkat_jam ?? '' }}</td>
                    <td>{{ $datas->kegiatan[$i]->tiba_jam ?? '' }}</td>
                    <td class="capitalize">{{ $datas->kegiatan[$i]->maskapai ?? ' ' }}</td>
                    <td class="uppercase">{{ $datas->kegiatan[$i]->payment_via ?? ' ' }}</td>
                </tr>
            @endfor

            {{-- ttd --}}
            <tr>
                <td class="text-center" colspan="2">Karyawan yang akan PD</td>
                <td class="text-center" colspan="2">Menyetujui</td>
                <td class="text-center" colspan="2">Mengetahui</td>
                <td class="text-center" colspan="2">Mengetahui</td>
            </tr>
            <tr>
                <td class="text-center" colspan="2">
                    <p>Tanggal: {{ Carbon\Carbon::parse($datas->created_at)->format('d M Y') }}</p>
                    <img src="data:image/png;base64, {{ $signuser }}" alt="">
                    <p class="uppercase">({{ $datas->pegawai->nama }})</p>
                </td>
                <td class="text-center" colspan="2">
                    <p>Chief/Dept Head/ <br> Region Head</p>
                    <img src="data:image/png;base64, {{ $sign2 }}" alt="">
                    <p class="uppercase">({{ $approval2->name ?? '-' }})</p>
                </td>
                <td class="text-center" colspan="2">
                    <p>ADH</p>
                    <img src="data:image/png;base64, {{ $sign3 }}" alt="">
                    <p class="uppercase">({{ $approval3->name ?? '' }})</p>
                </td>
                <td class="text-center" colspan="2">
                    <p>General Affair</p>
                    <img src="data:image/png;base64, {{ $sign1 }}" alt="">
                    <p class="uppercase">({{ $approval1->name ?? '' }})</p>
                </td>
            </tr>
        </table>
        <span>Prepayment PD harus diselesaikan 7 hari setelah kembali</span>

    </div>

</body>


</html>
