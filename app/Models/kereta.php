<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kereta extends Model
{
    protected $primaryKey = 'ID_Kereta';
    protected $fillable = [
        'Kode_Kereta', 'nama_kereta', 'kapasitas', 'tipe', 'foto_kereta'
    ];

    public function jadwals()
    {
        return $this->hasMany(jadwal::class, 'id_kereta', 'ID_Kereta');
    }
}
