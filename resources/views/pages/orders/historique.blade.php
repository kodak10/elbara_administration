@extends('layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Historique des Commandes</h2>
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
                        <h3 class="card-title">Liste des Commandes</h3>
                    </div>
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <div class="text-secondary">
                                Afficher
                                <div class="mx-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" value="8" size="3" aria-label="Invoices count">
                                </div>
                                entrées
                            </div>
                            <div class="ms-auto text-secondary">
                                Rechercher :
                                <div class="ms-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" placeholder="Rechercher...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-selectable card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all orders"></th>
                                    <th>No.</th>
                                    <th>Libellé</th>
                                    <th>Client</th>
                                    <th>Départ</th>
                                    <th>Destination</th>
                                    <th>Montant</th>
                                    <th>Status</th>
                                    <th>Mode de Paiement</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td><input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox"></td>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->libelle }}</td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    <td>{{ $order->depart_adresse }}</td>
                                    <td>{{ $order->destination_adresse }}</td>
                                    <td>{{ number_format($order->montant, 2, ',', ' ') }} FCFA</td>
                                    <td>
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
                                    </td>
                                    <td>{{ $order->mode_payment }}</td>
                                    <td class="text-end">
                                        <span class="dropdown">
                                            <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('orders.show', $order->id) }}">Voir</a>
                                                <a class="dropdown-item" href="#">Annuler</a>
                                            </div>
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">Aucune commande trouvée</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
