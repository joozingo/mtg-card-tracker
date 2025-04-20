<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruling extends Model
{
    protected $fillable = ['oracle_id', 'comment', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
