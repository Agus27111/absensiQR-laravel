<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Multitenantable
{
    protected static function bootMultitenantable()
    {
        // Hanya jalankan scope jika sedang di Front-end (bukan saat login/console)
        if (auth()->check()) {
            static::addGlobalScope('sekolah_id', function (Builder $builder) {
                // Jangan filter jika dia super_admin
                if (!auth()->user()->super_admin) {
                    $builder->where(static::query()->getModel()->getTable() . '.sekolah_id', auth()->user()->sekolah_id);
                }
            });
        }

        static::creating(function ($model) {
            if (auth()->check() && !isset($model->sekolah_id)) {
                $model->sekolah_id = auth()->user()->sekolah_id;
            }
        });
    }
}
