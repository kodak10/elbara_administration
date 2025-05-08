<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('livreur.{livreurId}', function ($user, $livreurId) {
    return (int) $user->id === (int) $livreurId;
});

