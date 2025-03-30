<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FinanceController extends Controller
{
    public function pointLivreur(Request $request)
{
    // Log pour déboguer les valeurs reçues
    Log::info('Paramètres de la requête reçus', ['start_date' => $request->start_date, 'end_date' => $request->end_date]);

    // Validation des dates
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    // Récupérer les paramètres de la requête
    $startDate = Carbon::parse($request->start_date)->startOfDay();  // Inclure toute la journée du début
    $endDate = Carbon::parse($request->end_date)->endOfDay();        // Inclure toute la journée du dernier jour

    Log::info('Dates après traitement', ['start_date' => $startDate, 'end_date' => $endDate]);

    // Récupérer les commandes des livreurs pendant la période
    $orders = Order::whereBetween('date', [$startDate, $endDate])
                    ->whereNotNull('livreur_id') // Assurez-vous que la commande est assignée à un livreur
                    ->get();

    Log::info('Commandes récupérées pour la période', ['order_count' => $orders->count()]);

    // Calculer le total des montants pour chaque livreur
    $livreursFinanciers = $orders->groupBy('livreur_id')->map(function ($orders) {
        return $orders->sum('montant');  // Total des montants des commandes pour un livreur
    });

    Log::info('Total des montants calculé pour chaque livreur', ['livreurs_financiers' => $livreursFinanciers]);

    // Récupérer les noms des livreurs
    $livreurs = [];
    foreach ($livreursFinanciers as $livreurId => $total) {
        $livreurs[] = [
            'livreur' => User::find($livreurId)->name, // Nom du livreur
            'total' => $total, // Montant total des commandes
        ];
    }

    Log::info('Livreurs et leurs totaux générés', ['livreurs' => $livreurs]);

    // Affichage des informations dans la vue
    return view('pages.finances.livreurs', compact('livreurs', 'startDate', 'endDate'));
}

}
