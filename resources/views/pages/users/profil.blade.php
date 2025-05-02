@extends('layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Paramètres du compte</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card">
            <div class="row g-0">
                <div class="col-12 col-md-9 d-flex flex-column">
                    <div class="card-body">
                        <h2 class="mb-4">Mon Compte</h2>

                        <!-- Début du formulaire -->
                        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Détails du Profil -->
                            <h3 class="card-title">Détails du Profil</h3>
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img src="{{ $user->image ? Storage::url($user->image) : asset('storage/profile-default.webp') }}" alt="Avatar" class="avatar-xxl" style="height: 100px; width:100px;">
                                </div>
                                <div class="col-auto">
                                    <input type="file" name="avatar" class="form-control mb-2">
                                    @if($user->image)
                                        <a href="{{ route('profil.avatar.destroy') }}" class="btn btn-ghost-danger btn-3"  onclick="event.preventDefault(); document.getElementById('delete-avatar-form').submit();">
                                            Supprimer avatar
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Nom -->
                            <h3 class="card-title mt-4">Nom</h3>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>

                            <!-- Numéro de téléphone -->
                            <h3 class="card-title mt-4">Numéro de téléphone</h3>
                            <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">

                            <!-- Email -->
                            <h3 class="card-title mt-4">Email</h3>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>

                            <!-- Mot de passe -->
                            <h3 class="card-title mt-4">Mot de passe</h3>
                            <p class="card-subtitle">Pour changer votre mot de passe, entrez votre mot de passe actuel et le nouveau mot de passe.</p>
                            <div class="row g-3">
                                <div class="col-md">
                                    <div class="form-label">Mot de passe actuel</div>
                                    <input type="password" class="form-control" name="current_password" placeholder="Mot de passe actuel">
                                </div>
                                <div class="col-md">
                                    <div class="form-label">Nouveau mot de passe</div>
                                    <input type="password" class="form-control" name="password" placeholder="Nouveau mot de passe">
                                </div>
                                <div class="col-md">
                                    <div class="form-label">Confirmer le nouveau mot de passe</div>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmer le mot de passe">
                                </div>
                            </div>

                            <!-- Boutons de validation -->
                            <div class="card-footer bg-transparent mt-auto">
                                <div class="btn-list justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-2">Valider</button>
                                </div>
                            </div>
                        </form>
                        <!-- Fin du formulaire -->

                        <!-- Formulaire caché pour suppression avatar -->
                        @if($user->image)
                            <form id="delete-avatar-form" action="{{ route('profil.avatar.destroy') }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection