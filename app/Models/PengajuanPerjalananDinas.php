<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanPerjalananDinas extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_perjalanan_dinas';
    protected $guarded = ['bank'];

    protected $casts = [
        'no_surat' => 'integer'
    ];

    protected $appends = ['kegiatan'];

    /**
     * Get all of the kegiatan for the PengajuanPerjalananDinas
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kegiatan_perjalanan_dinas(): HasMany
    {
        return $this->hasMany(KegiatanPerjalananDinas::class);
    }

    /**
     * Get the golongan that owns the PengajuanPerjalananDinas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gol(): BelongsTo
    {
        return $this->belongsTo(Golongan::class, 'golongan', 'id');
    }

    protected function kegiatan(): Attribute
    {
        return new Attribute(
            get: fn () => $this->kegiatan_perjalanan_dinas,
        );
    }

    /**
     * Get the rekening that owns the PengajuanPerjalananDinas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rekening(): BelongsTo
    {
        return $this->belongsTo(Rekening::class, 'no_rekening', 'rekening');
    }
}
