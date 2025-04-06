<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\ActivityLogger; 

class DemandeLivreur extends Model
{
    use HasFactory, ActivityLogger;

    protected $fillable = [
        'nom',
        'prenoms',
        'numero_telephone',
        'lieu_residence',
        'a_moto',
        'message',
    ];

    public function livreur()
    {
        return $this->hasOne(Livreur::class);
    }
}
