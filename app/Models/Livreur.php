<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\ActivityLogger;

class Livreur extends Model
{
    use ActivityLogger;
    protected $fillable = [
        'user_id', // Ajout de l'ID utilisateur
        'code', 
        'nom', 
        'prenoms', 
        'type',
        'numero_telephone', 
        'lieu_residence', 
        'informations_complementaires', 
        // Ajoutez d'autres colonnes que vous souhaitez permettre l'assignation en masse
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
