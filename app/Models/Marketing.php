<?php

namespace App\Models;

use App\Models\Traits\ActivityLogger; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
    use HasFactory, ActivityLogger;

    protected $fillable = [
        'name',
        'image',
    ];
}
