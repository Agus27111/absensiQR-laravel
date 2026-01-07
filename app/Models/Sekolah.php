<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_sekolah',
        'npsn',
        'jam_masuk',
        'logo',
        'subscription_until',
        'status_langganan',
    ];

    protected $casts = [
        'subscription_until' => 'datetime',
    ];

    /**
     * Get all murids for this sekolah.
     */
    public function murids()
    {
        return $this->hasMany(Murid::class, 'sekolah_id');
    }

    /**
     * Get all users for this sekolah.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'sekolah_id');
    }

    /**
     * Get all jenjangs for this sekolah.
     */
    public function jenjangs()
    {
        return $this->hasMany(Jenjang::class, 'sekolah_id');
    }
}
