<?php

namespace App\Models;

use App\Casts\CreatedAtCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = [
        'casa_marca'
    ];


    protected $casts = [
        'created_at' => CreatedAtCast::class,
    ];

}
