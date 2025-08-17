<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class penumpang extends Model
{
    protected $primaryKey = 'ID_Penumpang';
    protected $fillable = [
        'Kode_Penumpang', 'id_booking', 'nama', 'no_telp', 'nik'
    ];

    public function booking()
    {
        return $this->belongsTo(booking::class, 'id_booking', 'ID_Booking');
    }

    public function tiket()
    {
        return $this->hasOne(tiket::class, 'id_penumpang', 'ID_Penumpang');
    }
}
