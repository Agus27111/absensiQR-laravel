<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Gate;

class IsAdmin extends Model
{
    use HasFactory;

    public function isAdmin()
    {
        if (! Gate::allows('admin')) {
            abort(403);
        }
    }
}
