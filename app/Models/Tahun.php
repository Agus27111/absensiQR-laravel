<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tahun extends Model
{
    use HasFactory, Multitenantable;
    protected $guarded = ['id'];

    public function murid() 
    {
        return $this->hasMany(Murid::class);
    }
}
