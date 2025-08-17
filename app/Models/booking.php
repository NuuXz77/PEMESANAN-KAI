<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class booking extends Model
{
    protected $primaryKey = 'ID_Booking';
    protected $fillable = [
        'Kode_Booking', 'id_akun', 'id_jadwal', 'total_harga', 'status'
    ];

    public function akun()
    {
        return $this->belongsTo(User::class, 'id_akun', 'ID_Akun');
    }

    public function jadwal()
    {
        return $this->belongsTo(jadwal::class, 'id_jadwal', 'ID_Jadwal');
    }

    public function penumpangs()
    {
        return $this->hasMany(penumpang::class, 'id_booking', 'ID_Booking');
    }

    public function pembayaran()
    {
        return $this->hasOne(pembayaran::class, 'id_booking', 'ID_Booking');
    }
}
