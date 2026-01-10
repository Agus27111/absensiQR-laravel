<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory, Multitenantable;

    protected $fillable = [
        'sekolah_id',
        'order_id',
        'status',
        'gross_amount',
        'snap_token',
        'payment_type'
    ];
}
