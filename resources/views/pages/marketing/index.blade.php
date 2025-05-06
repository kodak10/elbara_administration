@extends('layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Slides</h2>
            </div>
            <div class="col">
                <a href="#" class="btn btn-2 float-end" data-bs-toggle="modal" data-bs-target="#modal-report">
                    Ajouter un Slide
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            @foreach ($marketings as $marketing)
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-4 text-center">
                            @if($marketing->image)
                                <img src="{{ asset('storage/' . $marketing->image) }}" alt="{{ $marketing->name }}" class="avatar avatar-xl mb-3 rounded">
                            @else
                                <span class="avatar avatar-xl mb-3 rounded bg-secondary text-white d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image"></i>
                                </span>
                            @endif
                            <h3 class="m-0 mb-1"><a href="#">{{ $marketing->name }}</a></h3>
                        </div>
                        <div class="d-flex">
                            

                            <a href ="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $marketing->id }}" class="card-btn"> Modifier</a>
                      
                            <form action="{{ route('marketings.destroy', $marketing->id) }}" method="POST" class="card-btn ms-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0" onclick="return confirm('Supprimer ce partenaire ?')">
                                    <i class="fas fa-trash-alt me-1 text-danger"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                @foreach ($marketings as $marketing)
                  <!-- Modal d'édition -->
                  <div class="modal fade" id="editModal{{ $marketing->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $marketing->id }}" aria-hidden="true">
                      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                          <div class="modal-content">
                              <form action="{{ route('marketings.update', $marketing->id) }}" method="POST" enctype="multipart/form-data">
                                  @csrf
                                  @method('PUT')
                                  <div class="modal-header">
                                      <h5 class="modal-title">Modifier le Slide</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                  </div>
                                  <div class="modal-body">
                                      <div class="mb-3">
                                          <label class="form-label">Nom</label>
                                          <input type="text" name="name" class="form-control" value="{{ $marketing->name }}" required>
                                      </div>
                                      
                                      <div class="mb-3">
                                          <label class="form-label">Image du Slide (laisser vide pour ne pas changer) </label>
                                          <label class="form-label">Dimenssion recommandée : 1112 x 588 </label>

                                          <input type="file" name="image" class="form-control" accept="image/*">
                                          @if($marketing->image)
                                              <div class="mt-2">
                                                  <img src="{{ asset('storage/' . $marketing->image) }}" width="100" class="rounded">
                                              </div>
                                          @endif
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                                          Annuler
                                      </button>
                                      <button type="submit" class="btn btn-primary">
                                          Mettre à jour
                                      </button>
                                  </div>
                              </form>
                          </div>
                      </div>
                  </div>
                  @endforeach

            @endforeach
        </div>
    </div>
</div>

<!-- Modal d'ajout -->
<div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('marketings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un Slide dans l'application Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image (obligatoire)</label>
                        <label class="form-label">Dimenssion recommandée : 1112 x 588 </label>
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
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
