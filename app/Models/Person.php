<?php

namespace App\Models;

use App\Casts\CreatedAtCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lastname',
        'fullname',
        'type_document',
        'number_document',
        'direction',
        'phone',
        'email'
    ];

    protected $casts = [
        'created_at' => CreatedAtCast::class,
    ];
}
