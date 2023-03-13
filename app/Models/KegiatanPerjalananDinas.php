<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KegiatanPerjalananDinas extends Model
{
    use HasFactory;
    protected $table = 'kegiatan_perjalanan_dinas';
    protected $guarded = [
        'pengajuan_perjalanan_dinas_id'
    ];
}
