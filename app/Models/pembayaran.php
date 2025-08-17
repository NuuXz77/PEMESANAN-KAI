<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pembayaran extends Model
{
    protected $primaryKey = 'ID_Pembayaran';
    protected $fillable = [
        'Kode_Pembayaran', 'id_booking', 'waktu_bayar', 'metode_bayar', 'nominal', 'status'
    ];

    public function booking()
    {
        return $this->belongsTo(booking::class, 'id_booking', 'ID_Booking');
    }
}
