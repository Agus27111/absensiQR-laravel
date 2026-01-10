<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Murid extends Model
{
    use HasFactory, Multitenantable;

    // Ini untuk mengijinkan Laravel mengisi tabel database dengan nama berikut
    // jika menggunakan tinker
    protected $guarded = ['id'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahun()
    {
        return $this->belongsTo(Tahun::class);
    }

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }
}
