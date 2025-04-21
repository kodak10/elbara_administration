<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DemandeLivreur;
use App\Models\Livreur;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LivreurController extends Controller
{

    public function demandeLivreur(Request $request)
    {
        Log::info('Received demande livreur request', $request->all());

        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenoms' => 'required|string|max:255',
                'numero_telephone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'lieu_residence' => 'nullable|string|max:255',
                'image' => 'nullable|image', // nullable au cas où pas d'image
            ]);

            // Créer la demande sans l'image
            $demande = DemandeLivreur::create($validated);

            // Upload de l'image si elle existe
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $demande->id . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/demandes', $fileName);
                
                // Mettre à jour le chemin de l'image dans la base de données
                $demande->update([
                    'image' => 'demandes/' . $fileName,
                ]);
            }
            
            

            Log::info('Demande created successfully', ['id' => $demande->id]);

            return response()->json([
                'success' => true,
                'message' => 'Demande enregistrée avec succès',
                'data' => $demande
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating demande: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getLivreurInfo(Request $request)
    {
        Log::info('Début de getLivreurInfo');
        
        try {
            $user = $request->user();
            Log::info('Utilisateur trouvé:', ['user_id' => $user->id]);
            
            // Vérifiez que la relation 'driver' existe dans votre modèle User
            if (!$user->relationLoaded('livreur')) {
                $user->load('livreur');
            }
            
            $livreur = $user->livreur;
            
            if (!$livreur) {
                Log::warning('Aucun livreur associé à cet utilisateur');
                return response()->json([
                    'success' => false,
                    'message' => 'Driver not found'
                ], 404);
            }
            
            Log::info('Livreur trouvé:', [
                'livreur_id' => $livreur->id,
                'name' => $livreur->nom,
                'name' => $livreur->prenoms,
                'phone' => $livreur->numero_telephone
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $livreur->nom,
                    'name' => $livreur->prenoms,
                    'phone' => $livreur->numero_telephone,
                    'id' => $livreur->id,
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
                'status' => 'required|in:actif,inactif'
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



    public function getOrdersByStatus(Request $request)
    {
        Log::info("[OrderController] Début getOrdersByStatus", ['status_reçue' => $request->query('status')]);
    
        try {
            $user = $request->user();
            Log::info("[OrderController] Utilisateur authentifié", ['user_id' => $user->id]);
    
            $livreur = $user->livreur;
            if (!$livreur) {
                Log::warning("[OrderController] Livreur non trouvé pour l'utilisateur", ['user_id' => $user->id]);
                return response()->json(['error' => 'Livreur non trouvé'], 404);
            }
    
            Log::info("[OrderController] Livreur trouvé", ['livreur_id' => $livreur->id]);
    
            $status = $request->query('status', 'pending');
            $statusMapping = [
                'pending' => 'En attente',
                'completed' => 'Terminée',
                'cancelled' => 'Annulée',
                'in_progress' => ['En cours', 'Acceptée']
            ];
    
            if (!array_key_exists($status, $statusMapping)) {
                Log::warning("[OrderController] Statut invalide reçu", ['status' => $status]);
                return response()->json(['error' => 'Statut invalide'], 400);
            }
    
            Log::info("[OrderController] Statut valide", ['status' => $status, 'mapping' => $statusMapping[$status]]);
    
            $query = Order::with(['user', 'depart', 'destination'])
                ->where('livreur_id', $livreur->id);
    
            if ($status === 'in_progress') {
                $query->whereIn('status_orders', $statusMapping[$status]);
                Log::info("[OrderController] Recherche de commandes avec plusieurs statuts", ['statuts' => $statusMapping[$status]]);
            } else {
                $query->where('status_orders', $statusMapping[$status]);
                Log::info("[OrderController] Recherche de commandes avec un seul statut", ['statut' => $statusMapping[$status]]);
            }
    
            $orders = $query->orderBy('created_at', 'desc')->get();
    
            Log::info("[OrderController] Nombre de commandes récupérées", ['nombre' => $orders->count()]);
    
            $ordersMapped = $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'client_name' => $order->user->name ?? 'Client inconnu',
                    'client_phone' => $order->user->phone ?? $order->numero_destinateur,
                    'depart_adresse' => $order->depart_adresse,
                    'destination_adresse' => $order->destination_adresse,
                    'depart_latitude' => $order->depart->latitude ?? null,
                    'depart_longitude' => $order->depart->longitude ?? null,
                    'destination_latitude' => $order->destination->latitude ?? null,
                    'destination_longitude' => $order->destination->longitude ?? null,
                    'montant' => $order->montant,
                    'status_orders' => $order->status_orders,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'rating' => $order->rating ?? 0,
                    'total_ratings' => $order->user->ratings()->count() ?? 0,
                    'numero_destinateur' => $order->numero_destinateur,
                ];
            });
    
            Log::info("[OrderController] Fin de traitement getOrdersByStatus");
    
            return response()->json([
                'success' => true,
                'data' => $ordersMapped
            ]);
    
        } catch (\Exception $e) {
            Log::error("[OrderController] Exception capturée", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Annule une commande
     */
    public function cancelOrder(Request $request, $orderId)
    {
        Log::info("[OrderController] Tentative d'annulation de commande", ['order_id' => $orderId]);

        try {
            $user = $request->user();
            $livreur = $user->livreur;

            if (!$livreur) {
                return response()->json(['error' => 'Livreur non trouvé'], 404);
            }

            $order = Order::where('id', $orderId)
                ->where('livreur_id', $livreur->id)
                ->first();

            if (!$order) {
                return response()->json(['error' => 'Commande non trouvée'], 404);
            }

            if ($order->status_orders !== 'En attente') {
                return response()->json([
                    'error' => 'Seules les commandes en attente peuvent être annulées'
                ], 400);
            }

            $order->update([
                'status_orders' => 'Annulée',
                'cancelled_at' => now(),
                'cancelled_by' => 'livreur'
            ]);

            Log::info("[OrderController] Commande annulée avec succès");
            return response()->json([
                'success' => true,
                'message' => 'Commande annulée avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error("[OrderController] Erreur d'annulation: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = $request->user();
       // $livreur = $user->livreur;

        // if (!$livreur) {
        //     return response()->json(['error' => 'Livreur non trouvé'], 404);
        // }
        
       // $user = Auth::user();
        
        // Supprime l'ancienne image si elle existe
        if ($user->image) {
            Storage::delete($user->image);
        }

        // Stocke la nouvelle image
        $path = $request->file('image')->store('public/users');
        $user->image = str_replace('public/', '', $path);
        $user->save();

        return response()->json([
            'success' => true,
            'user' => $user,
            'message' => 'Image de profil mise à jour avec succès'
        ]);
    }
}
