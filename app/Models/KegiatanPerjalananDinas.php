<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class KegiatanPerjalananDinas extends Model
{
    use HasFactory;
    protected $table = 'kegiatan_perjalanan_dinas';
    protected $guarded = [
        'pengajuan_perjalanan_dinas_id'
    ];

    /**
     * Get the pengajuan_perjalanan_dinas that owns the PlanKegiatanPengajuanPerjalananDinas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengajuan_perjalanan_dinas(): BelongsTo
    {
        return $this->belongsTo(PlanPengajuanPerjalananDinas::class, 'pengajuan_perjalanan_dinas_id');
    }

    /**
     * Scope a query to export data.
     */
    public function scopeExportQuery(Builder $query, int $id): void
    {
        $query->where('pengajuan_perjalanan_dinas_id', $id);
    }
}
