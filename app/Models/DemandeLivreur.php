<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeLivreur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenoms',
        'numero_telephone',
        'lieu_residence',
        'a_moto'
    ];

    public function livreur()
    {
        return $this->hasOne(Livreur::class);
    }
}
