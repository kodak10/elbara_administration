<?php

namespace App\Http\Controllers;
use App\Models\Livreur;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FinanceController extends Controller
{
    public function pointLivreur(Request $request)
{
    // Vérifier si les dates sont passées dans la requête, sinon utiliser les dates par défaut (dernière semaine)
    if ($request->has('start_date') && $request->has('end_date')) {
        // Si les dates sont spécifiées dans la requête
        $startDate = Carbon::parse($request->start_date)->startOfDay(); // Assurez-vous que c'est bien une instance de Carbon
        $endDate = Carbon::parse($request->end_date)->endOfDay(); // Assurez-vous que c'est bien une instance de Carbon
    } else {
        // Si aucune date n'est spécifiée, définir la dernière semaine (du lundi au dimanche)
        $startDate = Carbon::now()->startOfDay(); // Date du jour, à 00:00
        $endDate = Carbon::now()->endOfDay(); // Date du jour, à 23:59
    }

    // Passer les dates sous un format compatible avec l'input de type date
    $startDateFormatted = $startDate->toDateString(); // "YYYY-MM-DD"
    $endDateFormatted = $endDate->toDateString(); // "YYYY-MM-DD"
    

    Log::info('Dates après traitement', ['start_date' => $startDate, 'end_date' => $endDate]);
//dd($startDate, $endDate);
    // Récupérer les commandes des livreurs pendant la période
    $orders = Order::whereBetween('date', [$startDate, $endDate])
                    ->whereNotNull('livreur_id')
                    ->get();
                //dd($orders);

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
            'code' => Livreur::find($livreurId)->code ,
            'livreur' => Livreur::find($livreurId)->nom . ' ' . Livreur::find($livreurId)->prenoms, // concaténer le nom et le prénom
            'total' => $total,
        ];
    }

    Log::info('Livreurs et leurs totaux générés', ['livreurs' => $livreurs]);

    // Affichage des informations dans la vue
    return view('pages.finances.livreurs', compact('livreurs', 'startDate', 'endDate'));
}



}
