<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rute extends Model
{
    protected $primaryKey = 'ID_Rute';
    protected $fillable = [
        'Kode_Rute', 'asal_id', 'tujuan_id', 'jarak_tempuh', 'durasi', 'jalur_rute'
    ];

    public function asal()
    {
        return $this->belongsTo(stasiun::class, 'asal_id', 'ID_Stasiun');
    }

    public function tujuan()
    {
        return $this->belongsTo(stasiun::class, 'tujuan_id', 'ID_Stasiun');
    }
}
