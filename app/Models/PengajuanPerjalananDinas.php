<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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

    protected $appends = ['kegiatan', 'status'];

    protected $rolesCanViewAllPengajuan = [
        'admin',
        'ga',
        'chief',
        'hrd'
    ];

    protected $roleWithColumnFilter = [
        'chief' => 'sign_user_at',
        'hrd' => 'sign_chief_at',
        'ga' => 'sign_hrd_at',
    ];

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

    protected function status(): Attribute
    {
        return new Attribute(
            get: function(){
                if(!$this->sign_user_at){
                    return config('helper.status_pengajuan.draft');
                }

                else if(!$this->sign_chief_at){
                    return config('helper.status_pengajuan.waiting-chief');
                }

                else if(!$this->sign_hrd_at){
                    return config('helper.status_pengajuan.waiting-hrd');
                }

                else if(!$this->sing_ga_at){
                    return config('helper.status_pengajuan.waiting-ga');
                }

                else {
                    return '-';
                }
            }
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

    /**
     * Get the pegawai that owns the PengajuanPerjalananDinas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    /**
     * Scope a query to get pengajuan.
     */
    public function scopeListPengajuanWithAuthorization(Builder $query): void
    {
        $this->authorizationPengjuan($query);
    }

    private function authorizationPengjuan($query): Builder
    {
        if($this->canViewAllPengajuan()){
            return $this->authorizationWhereRole($query);;
        }
        return $query->whereBelongsTo(auth()->user()->pegawai);;
    }

    private function canViewAllPengajuan()
    {
        $roleUser = auth()->user()->roles()->first()->name;
        return in_array(strtolower($roleUser), $this->rolesCanViewAllPengajuan);
    }

    private function authorizationWhereRole($query)
    {
        $roleUser = auth()->user()->roles()->first()->name;
        $columnWhere = match (strtolower($roleUser)) {
            $this->whereColumn(false) => $this->whereColumn(true),
            default => null
        };

        return $query->whereNotNull($columnWhere);
    }

    /**
     * foreach role untuk filter pengajuan
     * @param bool $column true if return column, false if return role
     */
    private function whereColumn(bool $column)
    {
        $roleUser = auth()->user()->roles()->first()->name;
        foreach ($this->roleWithColumnFilter as $key => $value ) {
            if(strtolower($roleUser) == strtolower($key)){
                $result = $column == true ? strtolower($value) : strtolower($key);
                return $result;
            }

        }
    }

    public function roleCanApproved(): bool
    {
        $roleUser = auth()->user()->roles()->first()->name;
        return in_array(strtolower($roleUser), array_keys($this->roleWithColumnFilter));
    }

    public function disableByRole()
    {
        $roleUser = auth()->user()->roles()->first()->name;
        $column = 'sign_' . strtolower($roleUser) . '_at';

        return $this->{$column} ? true : false;
    }

    public function processApprove($role)
    {
        $methodsApprove = [
            'chief' => 'approve'.ucfirst($role),
            'hrd' => 'approve'.ucfirst($role),
            'ga' => 'approve'.ucfirst($role)
        ];

        if(!array_key_exists($role, $methodsApprove)){
            throw new Exception('role tidak diizinkan');
        }

        $this->{$methodsApprove[$role]}();

    }

    public function approveChief()
    {
        $this->sign_chief_at = Carbon::now();
        $this->nama_chief_signed = auth()->user()->pegawai->nama;
        $this->save();
    }

    public function approveHrd()
    {
        $this->sign_hrd_at = Carbon::now();
        $this->nama_hrd_signed = auth()->user()->pegawai->nama;
        $this->save();
    }

    public function approveGa()
    {
        $this->sign_ga_at = Carbon::now();
        $this->nama_ga_signed = auth()->user()->pegawai->nama;
        $this->save();
    }
     
}
