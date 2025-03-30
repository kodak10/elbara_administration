@extends('layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <h2 class="page-title">Livreurs</h2>
        </div>
        <div class="col">
          <a href="#" class="btn btn-2 float-end" data-bs-toggle="modal" data-bs-target="#modal-report"> Ajouter </a>
        </div>
      </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            @foreach ($livreurs as $livreur)
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-4 text-center">
                            <span class="avatar avatar-xl mb-3 rounded" style="background-image: url({{ asset('static/avatars/' . $livreur->avatar) }})"></span>
                            <h3 class="m-0 mb-1"><a href="#">{{ $livreur->prenom }} {{ $livreur->nom }}</a></h3>
                            <div class="text-secondary">LIVREUR {{ $livreur->type }}</div>
                            <div class="mt-3">
                                <span class="badge bg-purple-lt">{{ $livreur->code }}</span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <a href="#" class="card-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2 text-muted icon-3">
                                    <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path>
                                    <path d="M3 7l9 6l9 -6"></path>
                                </svg>
                                Action
                            </a>
                            <a href="{{ route('livreurs.toggleStatus', $livreur->user->id) }}" class="card-btn">
                                @if($livreur->user->status == 'actif')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2 text-muted icon-3">
                                        <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path>
                                    </svg>
                                    Désactiver
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2 text-muted icon-3">
                                        <path d="M14 2a2 2 0 0 1 2 2v16a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-16a2 2 0 0 1 2 -2h4z"></path>
                                    </svg>
                                    Activer
                                @endif
                            </a>
                            
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex mt-4">
            <ul class="pagination ms-auto">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                        <!-- SVG icon for previous page -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M15 6l-6 6l6 6"></path>
                        </svg>
                        prev
                    </a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item active"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">5</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">
                        next
                        <!-- SVG icon for next page -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M9 6l6 6l-6 6"></path>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Modal for user creation -->
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
                            <input type="text" name="nom" class="form-control" required value="{{ old('nom') }}">
                            @error('nom')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Prénoms</label>
                            <input type="text" name="prenoms" class="form-control" required value="{{ old('prenoms') }}">
                            @error('prenoms')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Numéro de téléphone</label>
                            <input type="tel" name="numero_telephone" class="form-control" required value="{{ old('numero_telephone') }}">
                            @error('numero_telephone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label class="form-label">Lieu de résidence</label>
                            <input type="text" name="lieu_residence" class="form-control" required value="{{ old('lieu_residence') }}">
                            @error('lieu_residence')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Informations Complémentaires</label>
                            <textarea name="informations_complementaires" class="form-control" rows="3">{{ old('informations_complementaires') }}</textarea>
                            @error('informations_complementaires')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label class="form-label">Avez-vous une moto ?</label>
                            <select name="a_moto" class="form-select">
                                <option value="1" {{ old('a_moto') == 1 ? 'selected' : '' }}>Oui</option>
                                <option value="0" {{ old('a_moto') == 0 ? 'selected' : '' }}>Non</option>
                            </select>
                            @error('a_moto')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="Interne" {{ old('type') == 'Interne' ? 'selected' : '' }}>Interne</option>
                                <option value="Externe" {{ old('type') == 'Externe' ? 'selected' : '' }}>Externe</option>
                            </select>
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
