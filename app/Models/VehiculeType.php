<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehiculeType extends Model
{
    protected $fillable = [
        'title',
        'price_per_km',
        'additional_price',
        'risk_price',
        'image_path'
    ];

    protected $casts = [
        'price_per_km' => 'float',
        'additional_price' => 'float',
        'risk_price' => 'float'
    ];
}
