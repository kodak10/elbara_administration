<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'logo',
        'additional_info',
    ];

    public function gares()
    {
        return $this->belongsToMany(Gare::class, 'company_gare');
    }
}
