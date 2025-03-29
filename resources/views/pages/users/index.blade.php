@extends('layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Utilisateurs</h2>
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
            @foreach($users as $user)
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-4 text-center">
                            <span class="avatar avatar-xl mb-3 rounded" style="background-image: url({{ Storage::url($user->image ?? 'avatars/default-avatar.png') }})"></span>
                            <h3 class="m-0 mb-1"><a href="#">{{ $user->name }}</a></h3>
                            <div class="text-secondary">{{ $user->roles->pluck('name')->implode(', ') }}</div>
                            <div class="mt-3">
                                @foreach($user->roles as $role)
                                    <span class="badge bg-purple-lt">{{ ucfirst($role->name) }}</span>
                                @endforeach
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
                            <a href="{{ route('utilisateurs.toggleStatus', $user->id) }}" class="card-btn">
                              @if($user->status == 'actif')
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

        
    </div>
</div>

<div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Création d'utilisateur</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <!-- Form for creating a user -->
              <form method="POST" action="{{ route('utilisateurs.store') }}">
                  @csrf
                  <!-- Affichage des erreurs globales -->
                  @if ($errors->any())
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
                  <div class="row">
                      <div class="col-lg-6">
                          <div class="mb-3">
                              <label class="form-label">Role</label>
                              <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                                  <option value="SuperAdmin">SuperAdmin</option>
                                  <option value="Admin">Admin</option>
                                  <option value="Manager">Manager</option>
                              </select>
                              @error('role')
                                  <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>
                      </div>
                      <div class="col-lg-6">
                          <div class="mb-3">
                              <label class="form-label">Nom</label>
                              <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nom de l'utilisateur" value="{{ old('name') }}" required>
                              @error('name')
                                  <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-lg-6">
                          <div class="mb-3">
                              <label class="form-label">Email</label>
                              <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                              @error('email')
                                  <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>
                      </div>
                      <div class="col-lg-6">
                          <div class="mb-3">
                              <label class="form-label">Contact</label>
                              <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" required>
                              @error('phone_number')
                                  <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>
                      </div>
                  </div>
          </div>

          <div class="modal-footer">
              <a href="#" class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal"> Annuler </a>
              <button type="submit" class="btn btn-primary btn-5 ms-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-2">
                      <path d="M12 5l0 14"></path>
                      <path d="M5 12l14 0"></path>
                  </svg>
                  Ajouter
              </button>
          </div>
          </form>
      </div>
  </div>
</div>


@endsection
