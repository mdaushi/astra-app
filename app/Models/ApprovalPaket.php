<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApprovalPaket extends Model
{
    use HasFactory;

    protected $table = 'approval_paket';
    protected $guarded = [];

    /**
     * Get the approvalsatu that owns the ApprovalPaket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvalsatu(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'approval_1');
    }

    /**
     * Get the approvaldua that owns the ApprovalPaket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvaldua(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'approval_2');
    }

    /**
     * Get the approvaltiga that owns the ApprovalPaket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvaltiga(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'approval_3');
    }
    
}
