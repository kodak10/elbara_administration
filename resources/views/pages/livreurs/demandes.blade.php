@extends('layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <h2 class="page-title">Demande de livreurs</h2>
        </div>
        <div class="col">
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
                    <th>Nom Prenoms</th>
                    <th>Avez-Vous une moto ?</th>
                    <th>Numéro de téléphone</th>
                    <th>Lieu de résidence</th>
                    <th class="w-1"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($livreurs as $livreur)
                  <tr>
                    <td data-label="Nom Prenoms">
                      <div class="d-flex py-1 align-items-center">
                        <span class="avatar avatar-2 me-2" style="background-image: url({{ asset('static/avatars/' . $livreur->avatar) }})"></span>
                        <div class="flex-fill">
                          <div class="font-weight-medium">{{ $livreur->nom }} {{ $livreur->prenoms }}</div>
                        </div>
                      </div>
                    </td>
                    <td data-label="Avez-vous une moto ?">
                      <div>{{ $livreur->a_moto ? 'Oui' : 'Non' }}</div>
                    </td>
                    <td data-label="Numéro de téléphone">
                      <div>{{ $livreur->numero_telephone }}</div>
                    </td>
                    <td data-label="Lieu de résidence">
                      <div>{{ $livreur->lieu_residence }}</div>
                    </td>
                    <td>
                      <div class="btn-list flex-nowrap">
                        <div class="dropdown">
                          <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Actions</button>
                          <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#"> Approuver </a>
                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-motifRefus"> Refuser </a>
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

{{-- Modal de refus --}}
<div class="modal modal-blur fade" id="modal-motifRefus" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Motif de refus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="col-lg-12">
            <label class="form-label">Motif (bloquer sur un nombre de caractere)</label>
            <textarea class="form-control" rows="3" maxlength="200"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal"> Annuler </a>
          <a href="#" class="btn btn-primary btn-5 ms-auto" data-bs-dismiss="modal">Refuser</a>
        </div>
      </div>
    </div>
</div>

{{-- Modal de création de livreur --}}
<div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Création de Livreur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('livreurs.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Prénoms</label>
                        <input type="text" name="prenoms" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label class="form-label">Numéro de téléphone</label>
                        <input type="tel" name="numero_telephone" class="form-control" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Lieu de résidence</label>
                        <input type="text" name="lieu_residence" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <label class="form-label">Informations Complémentaires</label>
                        <textarea name="informations_complementaires" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label class="form-label">Avez-vous une moto ?</label>
                        <select name="a_moto" class="form-select">
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="Interne">Interne</option>
                            <option value="Externe">Externe</option>
                        </select>
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

@endsection
