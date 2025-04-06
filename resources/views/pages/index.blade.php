@extends('layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Aperçu</div>
                <h2 class="page-title">TABLEAU DE BORD</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">

            <!-- Commandes en cours / en Attentes -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Commandes en cours / en Attentes</h3>
                    </div>
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <div class="text-secondary">
                                Show
                                <div class="mx-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" value="8" size="3" aria-label="Invoices count">
                                </div>
                                entries
                            </div>
                            <div class="ms-auto text-secondary">
                                Search:
                                <div class="ms-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-selectable card-table table-vcenter text-nowrap datatable">
                          <thead>
                              <tr>
                                  <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"></th>
                                  <th class="w-1">No.</th>
                                  <th>Référence</th>
                                  <th>Client</th>
                                  <th>Numéro Destinataire</th>
                                  <th>Date</th>
                                  <th>Statut de Commande</th>
                                  <th>Montant</th>
                                  <th>Mode de Paiement</th>
                                  <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach($orders as $order)
                              <tr>
                                  <td><input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select invoice"></td>
                                  <td><span class="text-secondary">{{ $order->id }}</span></td>
                                  <td><a href="{{ route('orders.show', $order->id) }}" class="text-reset" tabindex="-1">{{ $order->reference_commande }}</a></td>
                                  <td>{{ $order->user->name ?? 'Client inconnu' }}</td>
                                  <td>{{ $order->numero_destinataire }}</td>
                                  <td>{{ \Carbon\Carbon::parse($order->date)->format('d M Y') }}</td>

                                  <td><span class="badge bg-{{ $order->status_orders == 'En cours' ? 'success' : ($order->status_orders == 'En attente' ? 'warning' : 'danger') }} me-1"></span> {{ $order->status_orders }}</td>
                                  <td>{{ $order->montant }} €</td>
                                  <td>{{ $order->mode_payment }}</td>
                                  <td class="text-end">
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
                  
                </div>
            </div>

            <!-- Notifications -->
            {{-- <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Notifications</h3>
                    </div>
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <div class="text-secondary">
                                Show
                                <div class="mx-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" value="8" size="3" aria-label="Invoices count">
                                </div>
                                entries
                            </div>
                            <div class="ms-auto text-secondary">
                                Search:
                                <div class="ms-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-selectable card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"></th>
                                    <th class="w-1">No.</th>
                                    <th>Subject</th>
                                    <th>Client</th>
                                    <th>Created</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification)
                                <tr>
                                    <td><input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select invoice"></td>
                                    <td><span class="text-secondary">{{ $notification->id }}</span></td>
                                    <td>{{ $notification->subject }}</td>
                                    <td>{{ $notification->user->name }}</td>
                                    <td>{{ $notification->created_at->format('d M Y') }}</td>
                                    <td><span class="badge bg-info me-1"></span> {{ $notification->status }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('notifications.show', $notification->id) }}" class="btn btn-primary">Voir</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}

            <!-- Transactions récentes -->
            {{-- <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Transactions récentes</h3>
                    </div>
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <div class="text-secondary">
                                Show
                                <div class="mx-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" value="8" size="3" aria-label="Invoices count">
                                </div>
                                entries
                            </div>
                            <div class="ms-auto text-secondary">
                                Search:
                                <div class="ms-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-selectable card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"></th>
                                    <th class="w-1">No.</th>
                                    <th>Amount</th>
                                    <th>Client</th>
                                    <th>Created</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td><input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select invoice"></td>
                                    <td><span class="text-secondary">{{ $transaction->id }}</span></td>
                                    <td>{{ $transaction->amount }} €</td>
                                    <td>{{ $transaction->user->name }}</td>
                                    <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                    <td><span class="badge bg-success me-1"></span> {{ $transaction->status }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-primary">Voir</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}

        </div>
    </div>
</div>
@endsection
