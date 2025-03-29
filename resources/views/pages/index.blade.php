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
                                              <a class="dropdown-item" href="{{ route('orders.show', $order->id) }}">Voir</a>
                                              <a class="dropdown-item" href="#">Affecter un Livreur</a>
                                              <a class="dropdown-item" href="#">Annuler</a>
                                          </div>
                                      </span>
                                  </td>
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
