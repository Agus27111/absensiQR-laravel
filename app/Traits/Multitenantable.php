<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Multitenantable
{
    protected static function bootMultitenantable()
    {
        static::addGlobalScope('sekolah_id', function (Builder $builder) {
            if (auth()->check() && !auth()->user()->super_admin) {
                $builder->where('sekolah_id', auth()->user()->sekolah_id);
            }
        });

        // Otomatis mengisi sekolah_id saat input data baru
        static::creating(function ($model) {
            if (auth()->check()) {
                // Hanya isi otomatis jika user memiliki sekolah_id (bukan super admin global)
                if (auth()->user()->sekolah_id) {
                    $model->sekolah_id = auth()->user()->sekolah_id;
                }
            }
        });
    }
}
