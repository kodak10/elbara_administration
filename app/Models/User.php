<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Traits\ActivityLogger;

class User extends Authenticatable implements MustVerifyEmail
{
    use ActivityLogger, HasApiTokens, HasFactory, Notifiable, HasRoles; 

    // Personnalisation de la description des événements (optionnel, si vous souhaitez une logique plus spécifique)
    public function getDescriptionForEvent(string $eventName): string
    {
        switch ($eventName) {
            case 'created':
                return "{$this->name} a été créé";
            case 'updated':
                return "{$this->name} a été mis à jour";
            case 'deleted':
                return "{$this->name} a été supprimé";
            case 'restored':
                return "{$this->name} a été restauré";
            case 'forceDeleted':
                return "{$this->name} a été définitivement supprimé";
            default:
                return "{$this->name} a effectué une action sur l'utilisateur.";
        }
    }

    public function createApiToken()
    {
        $token = Str::random(60);

        $this->api_token = hash('sha256', $token);
        $this->save();

        return $token;
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function deliveredOrders()
    {
        return $this->hasMany(Order::class, 'livreur_id');
    }

    public function livreur()
    {
        return $this->hasOne(Livreur::class);
    }
}
