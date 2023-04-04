<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakturEkspedisi extends Model
{
    use HasFactory;

    protected $table = 'faktur_ekspedisi';
    protected $guarded = [];
}
