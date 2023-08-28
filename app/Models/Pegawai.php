<?php

namespace App\Models;

use App\Models\Golongan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $guarded = [];

    // protected $fillable = [
    //     'nama',
    //     'npk',
    //     'golongan_id',
    //     'kode_area',
    //     'area',
    //     'user_id',
    //     'jabatan_id',
    //     'picture',
    //     'is_faktur_ekspedisi'
    // ];

    protected $casts = [
        'is_faktur_ekspedisi' => 'boolean',
        'jabatan' => 'array',
    ];

    protected $appends = ['email'];

    /**
     * Get the golongan that owns the Pegawai
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function golongan(): BelongsTo
    {
        return $this->belongsTo(Golongan::class, 'golongan_id', 'id');
    }

    /**
     * Get the golongan that owns the Pegawai
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function jabatan(): BelongsTo
    // {
    //     return $this->belongsTo(Jabatan::class);
    // }

    /**
     * Get the golongan that owns the Pegawai
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getEmailAttribute()
    {
        return $this->user->email;
    }

    /**
     * Get the approvalPaket that owns the Pegawai
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvalPaket(): BelongsTo
    {
        return $this->belongsTo(ApprovalPaket::class, 'approvals_id');
    }

    public function approvalsatu(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approval1', 'email');
    }

    /**
     * Get the approvaldua that owns the ApprovalPaket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvaldua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approval2', 'email');
    }

    /**
     * Get the approvaltiga that owns the ApprovalPaket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvaltiga(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approval3', 'email');
    }

    /**
     * The jabatan that belong to the Pegawai
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function jabatan(): BelongsToMany
    {
        return $this->belongsToMany(Jabatan::class);
    }
}
