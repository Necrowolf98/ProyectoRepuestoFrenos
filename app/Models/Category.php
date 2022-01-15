<?php

namespace App\Models;

use App\Casts\CreatedAtCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    protected $casts= [
        'created_at' => CreatedAtCast::class,
    ];
}
