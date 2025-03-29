@extends('layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Gares</h2>
            </div>
            <div class="col">
                <!-- Bouton pour ouvrir la modale -->
                <a href="#" class="btn btn-2 float-end" data-bs-toggle="modal" data-bs-target="#modal-report"> Ajouter </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body p-0">
                <div id="table-default" class="table-responsive">
                    <table class="table table-vcenter table-mobile-md card-table">
                        <thead>
                            <tr>
                                <th>Gares</th>
                                <th>Compagnies</th>
                                <th>Contact 01</th>
                                <th>Contact 02</th>
                                <th>Localisation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gares as $gare)
                                <tr>
                                    <td>{{ $gare->nom }}</td>
                                    <td>
                                        @foreach($gare->compagnies as $compagnie)
                                            {{ $compagnie->name }},
                                        @endforeach
                                    </td>
                                    <td>{{ $gare->contact_01 }}</td>
                                    <td>{{ $gare->contact_02 }}</td>
                                    <td>{{ $gare->localisation }}</td>
                                    <td>
                                      <div class="btn-list flex-nowrap">
                                          <div class="dropdown">
                                              <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                                              <div class="dropdown-menu dropdown-menu-end">
                                                  <a class="dropdown-item" href="#">Modifier</a>
                                                  <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-danger" data-company-id="{{ $gare->id }}">Supprimer</button>

                                              </div>
                                          </div>
                                      </div>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale pour Ajouter une Gare -->
<div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Création de Gare</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('gares.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Compagnies</label>
                                <select name="compagnie_ids[]" class="form-select" multiple required>
                                    @foreach($compagnies as $compagnie)
                                        <option value="{{ $compagnie->id }}">{{ $compagnie->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Nom de Gare</label>
                                <input type="text" class="form-control" name="name" placeholder="Nom de la Gare" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Contact 01</label>
                                <input type="text" class="form-control" name="contact_01" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Contact 02</label>
                                <input type="text" class="form-control" name="contact_02">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Localisation</label>
                                <input type="text" class="form-control" name="localisation" required>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div>
                                <label class="form-label">Informations Complémentaires</label>
                                <textarea class="form-control" name="informations_complementaires" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal"> Annuler </button>
                        <button type="submit" class="btn btn-primary btn-5 ms-auto">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->

<div class="modal modal-blur fade" id="modal-danger" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-status bg-danger"></div>
        <div class="modal-body text-center py-4">
          <!-- Icone d'alerte -->
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2 text-danger icon-lg">
            <path d="M12 9v4"></path>
            <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"></path>
            <path d="M12 16h.01"></path>
          </svg>
          <h3>Êtes-vous sûr ?</h3>
          <div class="text-secondary">Voulez-vous vraiment supprimer cette gare ? Cette action ne peut pas être annulée.</div>
        </div>
        <div class="modal-footer">
          <div class="w-100">
            <div class="row">
              <div class="col">
                <!-- Bouton Annuler -->
                <a href="#" class="btn btn-3 w-100" data-bs-dismiss="modal">Annuler</a>
              </div>
              <div class="col">
                <!-- Formulaire de suppression -->
                <form id="delete-form" action="{{ route('gares.destroy', $gare->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-4 w-100">Supprimer</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

@endsection
