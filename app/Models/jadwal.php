<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jadwal extends Model
{
    protected $primaryKey = 'ID_Jadwal';
    protected $fillable = [
        'Kode_Jadwal',
        'id_kereta',
        'id_rute',
        'waktu_keberangkatan',
        'waktu_kedatangan'
    ];

    protected $casts = [
        'waktu_keberangkatan' => 'datetime',
        'waktu_kedatangan' => 'datetime'
    ];

    public function kereta()
    {
        return $this->belongsTo(kereta::class, 'id_kereta', 'ID_Kereta');
    }

    public function rute()
    {
        return $this->belongsTo(rute::class, 'id_rute', 'ID_Rute');
    }

    public function bookings()
    {
        return $this->hasMany(booking::class, 'id_jadwal', 'ID_Jadwal');
    }
}
