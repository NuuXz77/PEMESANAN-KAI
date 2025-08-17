<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tiket extends Model
{
    protected $primaryKey = 'ID_Tiket';
    protected $fillable = [
        'Kode_Tiket', 'id_kursi', 'id_penumpang', 'tanggal_pesan', 'waktu'
    ];

    public function penumpang()
    {
        return $this->belongsTo(penumpang::class, 'id_penumpang', 'ID_Penumpang');
    }
}
