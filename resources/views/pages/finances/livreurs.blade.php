@extends('layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Point Financier des Livreurs</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body p-0">
                <!-- Formulaire pour choisir la période -->
                <form action="{{ route('livreurs.financier') }}" method="GET" class="p-3">
                    @csrf
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Date de début</label>
                                <input type="date" class="form-control" name="start_date" value="{{ old('start_date') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Date de fin</label>
                                <input type="date" class="form-control" name="end_date" value="{{ old('end_date') }}" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-5">Afficher les résultats</button>
                </form>

                <hr>

                <!-- Table des résultats -->
                @if (isset($livreurs) && count($livreurs) > 0)
                    <div id="table-default" class="table-responsive">
                        <table class="table table-vcenter table-mobile-md card-table">
                            <thead>
                                <tr>
                                    <th>Livreur</th>
                                    <th>Montant Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($livreurs as $livreur)
                                    <tr>
                                        <td data-label="Livreur">
                                            <div class="d-flex py-1 align-items-center">
                                                <div class="flex-fill">
                                                    <div class="font-weight-medium">{{ $livreur['livreur'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="Montant Total">
                                            <div>{{ number_format($livreur['total'], 2, ',', ' ') }} F CFA</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>Aucune donnée disponible pour cette période.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
