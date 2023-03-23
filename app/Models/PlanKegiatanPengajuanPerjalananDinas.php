<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanKegiatanPengajuanPerjalananDinas extends Model
{
    use HasFactory;

    protected $table = 'plan_kegiatan_pengajuan_dinas';
    protected $guarded = [
        'pengajuan_perjalanan_dinas_id'
    ];
}
