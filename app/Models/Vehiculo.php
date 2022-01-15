<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $fillable = [
        'repuestofreno_id',
        'casa_marca_id',
        'modelo',
        'anio_vehiculo'
    ];
}
