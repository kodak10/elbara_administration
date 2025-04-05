@extends('layouts.master')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Historique des Activités</h3>
          </div>
          <div class="card-body">
            <div class="divide-y">
              @forelse ($activities as $activity)
                <div class="py-3">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <span class="avatar avatar-md">
                        @php
                          $user = $activity->causer;
                        @endphp
                        @if ($user && $user->image)
                          <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}" />
                        @elseif ($user)
                          {{ strtoupper(substr($user->name, 0, 2)) }}
                        @else
                          ???
                        @endif
                      </span>
                    </div>
                    <div class="col">
                      <div class="fw-bold text-truncate">
                        {{ $user->name ?? 'Système' }}
                      </div>
                      <div class="text-muted small">
                        {{ $activity->description }} <br>
                        <span class="text-secondary">{{ $activity->created_at->diffForHumans() }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <div class="text-center py-5 text-muted">
                  Aucune activité enregistrée.
                </div>
              @endforelse
            </div>
          </div>
          @if ($activities->hasPages())
            <div class="card-footer">
              {{ $activities->links() }}
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
