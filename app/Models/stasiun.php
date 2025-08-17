<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class stasiun extends Model
{
    protected $primaryKey = 'ID_Stasiun';
    protected $fillable = [
        'Kode_Stasiun', 'nama_stasiun', 'kota', 'alamat', 'latitude', 'longitude', 'foto_stasiun'
    ];

    public function asalRutes()
    {
        return $this->hasMany(rute::class, 'asal_id', 'ID_Stasiun');
    }

    public function tujuanRutes()
    {
        return $this->hasMany(rute::class, 'tujuan_id', 'ID_Stasiun');
    }
}
