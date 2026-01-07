<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenjang extends Model
{
    use HasFactory; 
    use Multitenantable;

    protected $fillable = [
        'name',
        'sekolah_id',
    ];

    // Di dalam Model Murid.php
}
