<?php

namespace App\Models;

use App\Models\User;
use App\Models\Supplier;
use App\Casts\CreatedAtCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'people_id',
        'type_voucher',
        'serie_voucher',
        'number_voucher',
        'iva',
        'discount',
        'total',
        'state'
    ];

    protected $casts = [
        'created_at' => CreatedAtCast::class,
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
}
