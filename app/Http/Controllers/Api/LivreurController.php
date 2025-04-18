<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Livreur;
use Illuminate\Support\Facades\Log;

class LivreurController extends Controller
{

    public function getLivreurInfo(Request $request)
    {
        Log::info('Début de getLivreurInfo');
        
        try {
            $user = $request->user();
            Log::info('Utilisateur trouvé:', ['user_id' => $user->id]);
            
            // Vérifiez que la relation 'driver' existe dans votre modèle User
            if (!$user->relationLoaded('driver')) {
                $user->load('driver');
            }
            
            $driver = $user->driver;
            
            if (!$driver) {
                Log::warning('Aucun livreur associé à cet utilisateur');
                return response()->json([
                    'success' => false,
                    'message' => 'Driver not found'
                ], 404);
            }
            
            Log::info('Livreur trouvé:', [
                'driver_id' => $driver->id,
                'name' => $driver->name,
                'phone' => $driver->phone
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $driver->name,
                    'phone' => $driver->phone,
                    'id' => $driver->id,
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans getLivreurInfo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Récupère les statistiques des commandes pour un livreur
     */
    public function getOrderStats($userId)
{
    Log::info("[OrderStatsController] Début getOrderStats pour user ID: $userId");

    try {
        // On récupère d'abord le livreur qui a ce user_id
        $livreur = Livreur::where('user_id', $userId)->first();

        if (!$livreur) {
            return response()->json(['error' => 'Livreur non trouvé'], 404);
        }

        $today = now()->format('Y-m-d');
        Log::debug("[OrderStatsController] Date du jour: $today");

        $stats = [
            'total' => Order::where('livreur_id', $livreur->id)->count(),
            'pending' => Order::where('livreur_id', $livreur->id)
                            ->where('status_orders', 'En attente')
                            ->whereDate('created_at', $today)
                            ->count(),
            'in_progress' => Order::where('livreur_id', $livreur->id)
                            ->whereIn('status_orders', ['En cours', 'Acceptée'])
                            ->count(),
            'cancelled' => Order::where('livreur_id', $livreur->id)
                            ->where('status_orders', 'Annulée')
                            ->whereDate('created_at', $today)
                            ->count(),
        ];

        Log::debug("[OrderStatsController] Statistiques calculées:", $stats);
        Log::info("[OrderStatsController] getOrderStats terminé avec succès");

        return response()->json($stats);

    } catch (\Exception $e) {
        Log::error("[OrderStatsController] Erreur dans getOrderStats: " . $e->getMessage());
        return response()->json([
            'error' => 'Erreur lors de la récupération des statistiques',
            'details' => $e->getMessage()
        ], 500);
    }
}


    /**
     * Récupère les commandes en attente pour un livreur
     */
    public function getPendingOrders($userId)
    {
        Log::info("[OrderStatsController] Début getPendingOrders pour livreur ID: $userId");

        try {

            // On récupère d'abord le livreur qui a ce user_id
            $livreur = Livreur::where('user_id', $userId)->first();

            if (!$livreur) {
                return response()->json(['error' => 'Livreur non trouvé'], 404);
            }

            $orders = Order::with('user')
                ->where('livreur_id', $livreur->id)
                ->where('status_orders', 'En attente')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'depart_adresse' => $order->depart_adresse,
                        'destination_adresse' => $order->destination_adresse,
                        'numero_destinateur' => $order->numero_destinateur,
                        'montant' => $order->montant,
                        'status_orders' => $order->status_orders,
                        'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                        'user' => [
                            'name' => $order->user->name,
                            'image' => $order->user->image ?? null,
                            'rating' => $order->user->rating ?? 0,
                        ],
                        'distance_km' => $order->distance_km ?? 0,
                    ];
                });

            Log::debug("[OrderStatsController] Nombre de commandes en attente: " . count($orders));
            Log::info("[OrderStatsController] getPendingOrders terminé avec succès");

            return response()->json($orders);

        } catch (\Exception $e) {
            Log::error("[OrderStatsController] Erreur dans getPendingOrders: " . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la récupération des commandes en attente',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère les commandes en cours pour un livreur
     */
    public function getInProgressOrders($userId)
    {
        Log::info("[OrderStatsController] Début getInProgressOrders pour livreur ID: $userId");

        try {

             // On récupère d'abord le livreur qui a ce user_id
            $livreur = Livreur::where('user_id', $userId)->first();

            if (!$livreur) {
                return response()->json(['error' => 'Livreur non trouvé'], 404);
            }
            $orders = Order::
                where('livreur_id', $livreur->id)
                ->where('status_orders', 'En cours')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'depart_adresse' => $order->depart_adresse,
                        'destination_adresse' => $order->destination_adresse,
                        'numero_destinateur' => $order->numero_destinateur,
                        'montant' => $order->montant,
                        'status_orders' => $order->status_orders,
                        'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                        'user' => [
                            'name' => $order->user->name,
                            'image' => $order->user->image ?? null,
                            'rating' => $order->user->rating ?? 0,
                        ],
                        'distance_km' => $order->distance_km ?? 0,
                    ];
                });

            Log::debug("[OrderStatsController] Nombre de commandes en cours: " . count($orders));
            Log::info("[OrderStatsController] getInProgressOrders terminé avec succès");

            return response()->json($orders);

        } catch (\Exception $e) {
            Log::error("[OrderStatsController] Erreur dans getInProgressOrders: " . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la récupération des commandes en cours',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Met à jour le statut du livreur
     */
    public function updateLivreurStatus(Request $request)
    {
        Log::info("[OrderStatsController] Début updateLivreurStatus");
        Log::debug("[OrderStatsController] Données reçues:", $request->all());

        try {
            $validated = $request->validate([
                'livreur_id' => 'required|exists:livreurs,id',
                'status' => 'required|in:disponible,indisponible'
            ]);

            $livreur = Livreur::find($validated['livreur_id']);
            Log::debug("[OrderStatsController] Livreur trouvé:", ['id' => $livreur->id, 'ancien_statut' => $livreur->status]);

            $livreur->status = $validated['status'];
            $livreur->save();

            Log::debug("[OrderStatsController] Nouveau statut:", ['statut' => $livreur->status]);
            Log::info("[OrderStatsController] Statut livreur mis à jour avec succès");

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error("[OrderStatsController] Erreur dans updateLivreurStatus: " . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la mise à jour du statut',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
