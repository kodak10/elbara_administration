<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\ActivityLogger;

class Gare extends Model
{
    use HasFactory, ActivityLogger;


    protected $fillable = [
        'nom', 'contact_01', 'contact_02', 'localisation', 'informations_complementaires'
    ];

    // Définir la relation plusieurs-à-plusieurs avec les compagnies
    public function compagnies()
    {
        return $this->belongsToMany(Company::class, 'company_gare');
    }
}
