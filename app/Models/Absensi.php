<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absensi extends Model
{
    use HasFactory, Multitenantable;

    protected $guarded = ['id'];
   
    public function murid()
    {
        return $this->belongsTo(Murid::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
   
}
