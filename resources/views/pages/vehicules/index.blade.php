@extends('layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Types de Véhicules</h2>
            </div>
            <div class="col">
                <a href="#" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#modal-add-vehicle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Ajouter un Véhicule
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            @foreach ($vehicles as $vehicle)
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-body p-4 text-center">
                            @if($vehicle->image_path)
                                <img src="{{ asset('storage/' . $vehicle->image_path) }}" alt="{{ $vehicle->title }}" class="avatar avatar-xl mb-3 rounded">
                            @else
                                <span class="avatar avatar-xl mb-3 rounded bg-secondary text-white d-flex align-items-center justify-content-center">
                                    <i class="fas fa-car"></i>
                                </span>
                            @endif
                            <h3 class="m-0 mb-1">{{ $vehicle->title }}</h3>
                            <div class="text-muted">
                                <div>Prix/km: {{ number_format($vehicle->price_per_km, 2) }} FCFA</div>
                                <div>Supplément: {{ number_format($vehicle->additional_price, 2) }} FCFA</div>
                                <div>Risque: {{ number_format($vehicle->risk_price, 2) }} FCFA</div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $vehicle->id }}" class="card-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                    <path d="M16 5l3 3" />
                                </svg>
                                Modifier
                            </a>
                            <form action="#" method="POST" class="card-btn ms-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0" onclick="return confirm('Supprimer ce véhicule ?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 7l16 0" />
                                        <path d="M10 11l0 6" />
                                        <path d="M14 11l0 6" />
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                    </svg>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal d'édition -->
                <div class="modal fade" id="editModal{{ $vehicle->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifier le Véhicule</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Titre</label>
                                                <input type="text" name="title" class="form-control" value="{{ $vehicle->title }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Prix par km (FCFA)</label>
                                                <input type="number" step="0.01" name="price_per_km" class="form-control" value="{{ $vehicle->price_per_km }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Prix supplémentaire (FCFA)</label>
                                                <input type="number" step="0.01" name="additional_price" class="form-control" value="{{ $vehicle->additional_price }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Prix risque (FCFA)</label>
                                                <input type="number" step="0.01" name="risk_price" class="form-control" value="{{ $vehicle->risk_price }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Image (laisser vide pour ne pas changer)</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        @if($vehicle->image_path)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $vehicle->image_path) }}" width="100" class="rounded">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                                        Annuler
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal d'ajout -->
<div class="modal modal-blur fade" id="modal-add-vehicle" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un Type de Véhicule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Titre*</label>
                                <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                                @error('title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prix par km (FCFA)*</label>
                                <input type="number" step="0.01" name="price_per_km" class="form-control" required value="{{ old('price_per_km') }}">
                                @error('price_per_km')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Prix supplémentaire (FCFA)*</label>
                                <input type="number" step="0.01" name="additional_price" class="form-control" required value="{{ old('additional_price', 0) }}">
                                @error('additional_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prix risque (FCFA)*</label>
                                <input type="number" step="0.01" name="risk_price" class="form-control" required value="{{ old('risk_price', 0) }}">
                                @error('risk_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image*</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M14 4l0 4l-6 0l0 -4" />
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection