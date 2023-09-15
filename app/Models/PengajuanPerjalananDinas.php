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
use Illuminate\Support\Arr;

class PengajuanPerjalananDinas extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_perjalanan_dinas';
    protected $guarded = ['bank'];

    protected $casts = [
        'no_surat' => 'integer'
    ];

    protected $appends = ['kegiatan', 'status'];

    // protected $rolesCanViewAllPengajuan = [
    //     'admin',
    //     'ga',
    //     'chief',
    //     'hrd'
    // ];

    // protected $roleWithColumnFilter = [
    //     'chief' => 'sign_user_at',
    //     'hrd' => 'sign_chief_at',
    //     'ga' => 'sign_hrd_at',
    // ];

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
            get: function() {
                $order = config('approval.order');
                $columns = config('approval.roleWithColumnFilter');

                if($this->rejected_at){
                    return 'Ditolak';
                }
                 
                if(!$this->sign_user_at){
                    return config('helper.status_pengajuan.draft');
                }

                else if(!$this->{$columns[$order[0]]})
                {
                    return config('helper.status_pengajuan.waiting-'.$order[0]);
                }

                else if(!$this->{$columns[$order[1]]})
                {
                    return config('helper.status_pengajuan.waiting-'.$order[1]);
                }

                else if(!$this->{$columns[$order[2]]})
                {
                    return config('helper.status_pengajuan.waiting-'.$order[2]);
                }

                else {
                    return "Disetujui";
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
        if($this->canViewAllPengajuan($query)){
            return $this->authorizationWhereRole($query);
        }
        return $query->whereBelongsTo(auth()->user()->pegawai);;
    }

    private function canViewAllPengajuan($query)
    {
        $rolesUser = auth()->user()->getRoleNames()->toArray();

        $roleMatch = array_intersect(array_map('strtolower', $rolesUser), config('approval.order'));
        // jika approval, maka get data sesuai pegawainya
        if(count($roleMatch) > 0){
            return $this->queryHasApproval($query)->exists();
        }

        else if(in_array('admin', $rolesUser)){
            return true;
        }

        return false; //user
    }

    private function queryHasApproval($query)
    {
        return $query->whereHas('pegawai', function(Builder $query){
            $query->whereHas('approvalsatu', function(Builder $query){
                $query->whereHas('pegawai', function(Builder $query){
                    $query->where('npk', auth()->user()->pegawai->npk);
                });
            });
            $query->orWhereHas('approvaldua', function(Builder $query){
                $query->whereHas('pegawai', function(Builder $query){
                    $query->where('npk', auth()->user()->pegawai->npk);
                });
            });
            $query->orWhereHas('approvaltiga', function(Builder $query){
                $query->whereHas('pegawai', function(Builder $query){
                    $query->where('npk', auth()->user()->pegawai->npk);
                });
            });
        });
    }

    private function authorizationWhereRole($query)
    {

        $rolesUser = array_map('strtolower',auth()->user()->getRoleNames()->toArray());

        if(in_array('admin', $rolesUser)){
            return $query;
        }
        
        $approvals = config('approval.order');
        $rolesMatch = array_intersect($approvals, $rolesUser);

        $query = $this->queryHasApproval($query);

        foreach ($rolesMatch as $role ) {
            switch ($role) {
                case 'ga':
                    $query->orWhereNull('sign_ga_at');
                    break;
                case 'chief':
                    $query->orWhereNull('sign_chief_at');
                    break;
                case 'hrd':
                    $query->orWhereNull('sign_hrd_at');
                    break;
                default:
                    break;
            }
        }
        return $query;
    }

    private function orderingApproval()
    {
        $roleUser = auth()->user()->roles[0]->name;
        $orders = config('approval.order');
        $order = Arr::where($orders, function (string|int $value, int $key) use($roleUser) {
            return $value == strtolower($roleUser);
        });
        $order = array_keys($order)[0];

        $roleWithColumnFilter = config('approval.roleWithColumnFilter');
        $column = $roleWithColumnFilter[strtolower($roleUser)];
        $columnExcept = Arr::except($roleWithColumnFilter, strtolower($roleUser));
        $nextColumn = $roleWithColumnFilter[$orders[$order + 1] ?? null ] ?? null;
        $prevColumn = $roleWithColumnFilter[$orders[$order - 1] ?? null] ?? null;
        
        return [
            'order' => $order+1,
            'column' => $column,
            'next_column' => $nextColumn,
            'prev_column' => $prevColumn,
            'except' => array_values($columnExcept)
        ]; 
    }

    /**
     * foreach role untuk filter pengajuan
     * @param bool $column true if return column, false if return role
     */
    private function whereColumn(bool $column)
    {
        $roleUser = auth()->user()->roles()->first()->name;
        foreach (config('approval.roleWithColumnFilter') as $key => $value ) {
            if(strtolower($roleUser) == strtolower($key)){
                $result = $column == true ? strtolower($value) : strtolower($key);
                return $result;
            }

        }
    }

    public function roleCanApproved(): bool
    {
        $roleUser = auth()->user()->roles()->first()->name;
        return in_array(strtolower($roleUser), array_keys(config('approval.roleWithColumnFilter')));
    }

    public function disableByRole()
    {
        $roleUser = auth()->user()->roles()->first()->name;
        $column = 'sign_' . strtolower($roleUser) . '_at';

        if($this->rejected_at) {
            return true;
        }

        return $this->{$column} ? true : false;
    }

    public function processApprove($pegawai, array $roles)
    {
        foreach ($roles as $role) {
            $methodsApprove = [
                'chief' => 'approve'.ucfirst($role),
                'hrd' => 'approve'.ucfirst($role),
                'ga' => 'approve'.ucfirst($role)
            ];
    
            if(!array_key_exists(strtolower($role), $methodsApprove)){
                throw new Exception('role tidak diizinkan');
            }
    
            $this->{$methodsApprove[strtolower($role)]}($pegawai);
            
        }

    }

    public function approveChief($pegawai)
    {
        $this->sign_chief_at = Carbon::now();
        $this->nama_chief_signed = $pegawai->nama;
        $this->save();
    }

    public function approveHrd($pegawai)
    {
        $this->sign_hrd_at = Carbon::now();
        $this->nama_hrd_signed = $pegawai->nama;
        $this->no_surat = $this->maxNoSurat();
        $this->save();
    }

    public function approveGa($pegawai)
    {
        $this->sign_ga_at = Carbon::now();
        $this->nama_ga_signed = $pegawai->nama;
        $this->save();
    }

    public function maxNoSurat()
    {
        return $this->max('no_surat') + 1;
    }

    public function disableRejected()
    {
        if($this->rejected_at !== null){
            return true;
        }

        return false;
    }
     
}
