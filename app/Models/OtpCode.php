<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class OtpCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone_number',
        'code',
        'expires_at',
        'verified'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'verified' => 'boolean'
    ];

    /**
     * Vérifie si le code OTP est expiré
     */
    public function isExpired(): bool
    {
        return $this->expires_at && Carbon::now()->gt($this->expires_at);
    }

    /**
     * Scope pour les codes non expirés
     */
    public function scopeNotExpired($query)
    {
        return $query->where('expires_at', '>', Carbon::now());
    }

    /**
     * Scope pour les codes vérifiés
     */
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    /**
     * Scope pour les codes non vérifiés
     */
    public function scopeNotVerified($query)
    {
        return $query->where('verified', false);
    }
}