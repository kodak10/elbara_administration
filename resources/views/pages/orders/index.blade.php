@extends('layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Gestion des Commandes</h2>
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
                        <h3 class="card-title">Toutes les Commandes</h3>
                    </div>
                    <div class="card-body border-bottom py-3">
                        <!-- Filtres -->
                        <div class="d-flex">
                            <div class="text-secondary">
                                <form method="GET" action="{{ route('orders.index') }}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="status" class="form-label">Statut</label>
                                            <select class="form-select form-select-sm" name="status" id="status">
                                                <option value="">-- Tous --</option>
                                                <option value="En attente" @if(request('status') == 'En attente') selected @endif>En attente</option>
                                                <option value="Acceptée" @if(request('status') == 'Acceptée') selected @endif>Acceptée</option>
                                                <option value="En cours" @if(request('status') == 'En cours') selected @endif>En cours</option>
                                                <option value="Livrée" @if(request('status') == 'Livrée') selected @endif>Livrée</option>
                                                <option value="Annulée" @if(request('status') == 'Annulée') selected @endif>Annulée</option>
                                                <option value="Échouée" @if(request('status') == 'Échouée') selected @endif>Échouée</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="engin" class="form-label">Engin</label>
                                            <select class="form-select form-select-sm" name="engin" id="engin">
                                                <option value="">-- Tous --</option>
                                                <option value="Moto" @if(request('engin') == 'Moto') selected @endif>Moto</option>
                                                <option value="Camion" @if(request('engin') == 'Camion') selected @endif>Camion</option>
                                                <option value="Trycicle" @if(request('engin') == 'Trycicle') selected @endif>Trycicle</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="type_course" class="form-label">Type de Course</label>
                                            <select class="form-select form-select-sm" name="type_course" id="type_course">
                                                <option value="">-- Tous --</option>
                                                <option value="Course" @if(request('type_course') == 'Course') selected @endif>Course</option>
                                                <option value="Livraison" @if(request('type_course') == 'Livraison') selected @endif>Livraison</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary mt-4">Filtrer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-selectable card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"></th>
                                    <th>Commande</th>
                                    <th>Client</th>
                                    <th>Type de Course</th>
                                    <th>Status</th>
                                    <th>Montant</th>
                                    <th>Engin</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td><input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select invoice"></td>
                                        <td><a href="{{ route('orders.show', $order->id) }}" class="text-reset">{{ $order->reference_commande }}</a></td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->type_course }}</td>
                                        <td><span class="badge {{ $order->status_orders == 'Livrée' ? 'bg-success' : 'bg-warning' }}">{{ $order->status_orders }}</span></td>
                                        <td>{{ $order->montant }} CFA</td>
                                        <td>{{ $order->engin }}</td>
                                        <td>{{ $order->date }}</td>
                                        <td>
                                            <span class="dropdown">
                                                <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <!-- Voir les détails de la commande -->
                                                    <a class="dropdown-item" href="{{ route('orders.show', $order->id) }}">Voir</a>
                                                    
                                                    <!-- Affecter un livreur -->
                                                    <form action="{{ route('orders.assign', $order->id) }}" method="POST">
                                                        @csrf
                                                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#assignLivreurModal{{ $order->id }}">
                                                            Affecter un Livreur
                                                        </a>
                                                    </form>
                                                    
                                                    <!-- Annuler la commande -->
                                                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH') <!-- Méthode PATCH pour mise à jour -->
                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="this.closest('form').submit();">
                                                            Annuler
                                                        </a>
                                                    </form>
                                                </div>
                                            </span>
                                        </td>
                                        
                                        <!-- Modal pour affecter un livreur -->
                                        <div class="modal fade" id="assignLivreurModal{{ $order->id }}" tabindex="-1" aria-labelledby="assignLivreurModalLabel{{ $order->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="assignLivreurModalLabel{{ $order->id }}">Affecter un Livreur</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('orders.assign', $order->id) }}" method="POST">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="livreur_id" class="form-label">Sélectionner un Livreur</label>
                                                                <select name="livreur_id" id="livreur_id" class="form-control" required>
                                                                    <option value="">-- Choisir un livreur --</option>
                                                                    @foreach($livreurs as $livreur)
                                                                        <option value="{{ $livreur->id }}">{{ $livreur->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                                <button type="submit" class="btn btn-primary">Affecter</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
