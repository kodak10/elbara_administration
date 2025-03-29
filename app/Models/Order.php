<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'livreur_id', 'depart_long', 'depart_lat', 'destination_long', 'destination_lat',
        'depart_adresse', 'destination_adresse', 'numero_destinateur', 'numero_destinataire', 'libelle',
        'montant', 'distance_km', 'duree_minutes', 'reference_commande', 'date', 'engin', 'type_course', 
        'status_orders', 'status_payment', 'mode_payment', 'transaction_id', 'date_paiement', 'instructions',
        'status_livreur', 'notation', 'historique_statut'
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
        return $this->belongsTo(User::class, 'livreur_id');
    }

    // Optionnel: Si tu as un modèle Admin pour la gestion
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
