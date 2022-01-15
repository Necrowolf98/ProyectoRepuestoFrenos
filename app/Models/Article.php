<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'categorie_id',
        'code',
        'name',
        'sale_price',
        'stock',
        'description'
    ];
}
