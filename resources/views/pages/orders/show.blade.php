@extends('layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Détails de la Commande #{{ $order->reference_commande }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Détails de la Commande</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Informations Client</h5>
                                <p><strong>Nom : </strong>{{ $order->user->name ?? 'Non renseigné' }}</p>
                                <p><strong>Numéro Destinateur : </strong>{{ $order->numero_destinateur ?? 'Non renseigné' }}</p>
                                <p><strong>Numéro Destinataire : </strong>{{ $order->numero_destinataire ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Informations sur la Commande</h5>
                                <p><strong>Montant : </strong>{{ number_format($order->montant, 2, ',', ' ') }} FCFA</p>
                                <p><strong>Mode de paiement </strong>{{ $order->mode_payment }}</p>
                                <p><strong>Payer Par: </strong></p>
                                <p><strong>Date de la commande : </strong>{{ $order->date }}</p>
                                <p><strong>Status de la commande : </strong>
                                    <span class="badge 
                                        @if($order->status_orders == 'En attente') bg-warning 
                                        @elseif($order->status_orders == 'Acceptée') bg-primary 
                                        @elseif($order->status_orders == 'En cours') bg-info 
                                        @elseif($order->status_orders == 'Livrée') bg-success 
                                        @elseif($order->status_orders == 'Annulée') bg-danger 
                                        @else bg-secondary 
                                        @endif">
                                        {{ $order->status_orders }}
                                    </span>
                                </p>
                                <p><strong>Status paiement : </strong>

                            </div>
                        </div>
                        
                        <hr>

                        <h5>Informations de Livraison</h5>
                        <p><strong>Départ : </strong>{{ $order->depart_adresse }}</p>
                        <p><strong>Destination : </strong>{{ $order->destination_adresse }}</p>
                        <p><strong>Type de véhicule : </strong>{{ $order->engin }}</p>
                        <p><strong>Instructions : </strong>{{ $order->libelle ?? 'Aucune' }}</p>

                        <hr>

                        @if($order->historique_statut)
                            <h5>Historique des Statuts</h5>
                            <ul>
                                @foreach(json_decode($order->historique_statut) as $statut)
                                    @if(is_string($statut)) <!-- Vérifier si le statut est une chaîne -->
                                        <li>{{ $statut }}</li>
                                    @else
                                        <li>{{ json_encode($statut) }}</li> <!-- Si c'est un objet, le convertir en chaîne -->
                                    @endif
                                @endforeach
                            </ul>
                        @endif

                        @if($order->livreur_id)
                        
                        <h5>Informations du Livreur</h5>
                        <p><strong>Nom du Livreur : </strong>{{ $order->livreur->nom }} {{ $order->livreur->prenoms }}</p>
                        <p><strong>Status Livreur : </strong>{{ $order->status_livreur ?? 'Non renseigné' }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
