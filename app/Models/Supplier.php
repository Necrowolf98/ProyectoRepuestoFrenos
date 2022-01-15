<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'type_document',
        'number_document',
        'direction',
        'phone',
        'email',
        'in_charge',
        'phone_in_charge',
    ];

    use HasFactory;
}
