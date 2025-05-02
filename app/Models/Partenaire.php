<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\ActivityLogger; 

class Partenaire extends Model
{
    use HasFactory, ActivityLogger;

    protected $fillable = [
        'name',
        'short_description',
        'description',
        'phone',
        'email',
        'address',
        'logo_url',
    ];
}
