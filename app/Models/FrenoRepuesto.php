<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrenoRepuesto extends Model
{
    use HasFactory;

    protected $table = 'descripcion_repuestos';

    protected $fillable = [
        'repuestofreno_id',
        'clase',
        'medidas',
        'posicion'
    ];
}
