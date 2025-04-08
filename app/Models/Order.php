<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Traits\ActivityLogger;

class Order extends Model
{
    use HasFactory, ActivityLogger;

    protected $fillable = [
        'user_id',
        'depart_lat',
        'depart_long',
        'destination_lat',
        'destination_long',
        'depart_adresse',
        'destination_adresse',
        'numero_destinateur',
        'numero_destinataire',
        'libelle',
        'montant',
        'distance_km',
        'reference_commande',
        'date',
        'engin',
        'type_course',
        'status_orders',
        'status_payment',
        'mode_payment',
        'instructions',
        'livreur_id',
        'admin_id',
        'notation',
        'historique_statut'
    ];

    protected $casts = [
        'historique_statut' => 'array',
        'date' => 'date',
    ];

    // Assure-toi que 'date' est traité comme une instance de Carbon
    protected $dates = ['date']; // Cela indique à Laravel que 'date' est une date
    
    // Si nécessaire, tu peux définir des accesseurs pour formater la date
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->format('d M Y');
    }


    // Relation avec User (le client)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec User (le livreur)
    public function livreur()
    {
        return $this->belongsTo(Livreur::class, 'livreur_id');
    }

    // Optionnel: Si tu as un modèle Admin pour la gestion
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
