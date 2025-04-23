<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;


class OrderController extends Controller
{
    /**
     * Créer une nouvelle commande
     */
    public function store(Request $request)
    {
        Log::info('Données reçues pour création de commande :', $request->all());

        try {
            // Validation des données
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                
                // Coordonnées géographiques
                'depart_lat' => 'required|numeric|between:-90,90',
                'depart_long' => 'required|numeric|between:-180,180',
                'destination_lat' => 'required|numeric|between:-90,90',
                'destination_long' => 'required|numeric|between:-180,180',
                
                // Adresses
                'depart_adresse' => 'required|string|max:255',
                'destination_adresse' => 'required|string|max:255',
                
                // Contacts
                // 'numero_destinateur' => 'required|string|max:20',
                // 'numero_destinataire' => 'required|string|max:20',
                
                // Informations course
                'libelle' => 'nullable|string|max:255',
                'montant' => 'required|numeric|min:0',
                //'distance_km' => 'required|numeric|min:0',
                'duree_minutes' => 'nullable|integer|min:0',
                
                // Sélections
                'engin' => 'required|in:Moto,Camion,Tricycle',
                'mode_payement' => 'required|in:Espèces,Mobile Money,Carte Bancaire',
                'type_course' => 'nullable|in:Course,Livraison',
                
                // Instructions supplémentaires
                'instructions' => 'nullable|string|max:500',
            ]);

            // Génération d'une référence de commande unique
            $reference = Carbon::now()->format('Ymd') . '-' . Str::upper(Str::random(4));
            
            // Création de la commande avec valeurs par défaut
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'depart_lat' => $validated['depart_lat'],
                'depart_long' => $validated['depart_long'],
                'destination_lat' => $validated['destination_lat'],
                'destination_long' => $validated['destination_long'],
                'depart_adresse' => $validated['depart_adresse'],
                'destination_adresse' => $validated['destination_adresse'],
                // 'numero_destinateur' => $validated['numero_destinateur'],
                // 'numero_destinataire' => $validated['numero_destinataire'],
                'libelle' => $validated['libelle'] ?? null,
                'montant' => $validated['montant'],
                'distance_km' =>  0,
                //'duree_minutes' => $validated['duree_minutes'] ?? $this->calculateDuration($validated['distance_km']),
                'reference_commande' => $reference,
                'date' => now(),
                'engin' => $validated['engin'],
                'type_course' => $validated['type_course'] ?? 'Course',
                'status_orders' => 'En attente',
                'status_payment' => 'Non payé',
                'mode_payment' => $validated['mode_payement'], // Note: correction orthographique
                'instructions' => $validated['instructions'] ?? null,
                'status_livreur' => 'En attente',
            ]);

            return response()->json([
                'success' => true,
                'order' => $order,
                'message' => 'Commande créée avec succès'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur création commande: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Échec de la création de commande',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Implémentez votre logique de calcul de distance ici
        // Exemple simplifié (vous devriez utiliser une API comme Google Maps)
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        
        return round($miles * 1.609344, 2); // Retourne la distance en km
    }

    private function calculatePrice($distance, $engin)
    {
        // Logique de calcul du prix en fonction de la distance et du type d'engin
        $basePrice = 500; // Prix de base en FCFA
        $pricePerKm = 200; // Prix par km en FCFA
        
        if ($engin === 'Camion') {
            $pricePerKm = 300;
        } elseif ($engin === 'Tricycle') {
            $pricePerKm = 250;
        }
        
        return $basePrice + ($distance * $pricePerKm);
    }

    private function calculateDuration($distance)
    {
        // Estimation simplifiée : 2 minutes par km
        return round($distance * 2);
    }

    /**
     * Récupérer les détails d'une commande spécifique
     */
    public function show(Request $request, $id)
    {
        try {
            $order = Order::where('user_id', $request->user()->id)
                        ->findOrFail($id);

            return response()->json([
                'success' => true,
                'order' => $order
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch order'
            ], 500);
        }
    }

    /**
     * Récupérer les commandes d'un utilisateur
     */
    public function getUserOrders(Request $request, $user_id)
    {
        try {
            $limit = $request->input('limit', 5);
            $orders = Order::where('user_id', $user_id)
                ->orderBy('date', 'desc')
                ->take($limit)
                ->get()
                ->map(function ($order) {
                    return $this->formatOrder($order);
                });

            return response()->json($orders);

        } catch (\Exception $e) {
            Log::error('Erreur getOrders: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des commandes'
            ], 500);
        }
    }

    /**
     * Récupérer les détails d'une commande par référence
     */
    public function getOrderDetails($orderId)
    {
        try {
            $order = Order::where('reference_commande', $orderId)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $this->formatOrderDetails($order)
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Commande non trouvée'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur détails commande: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des détails'
            ], 500);
        }
    }

    /**
     * Formater une commande pour la réponse
     */
    private function formatOrder($order)
    {
        return [
            'id' => $order->id,
            'reference_commande' => $order->reference_commande,
            'date' => $order->date,
            'depart_adresse' => $order->depart_adresse,
            'destination_adresse' => $order->destination_adresse,
            'montant' => $order->montant,
            'status_orders' => $order->status_orders,
            'engin' => $order->engin,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
        ];
    }

    /**
     * Formater les détails d'une commande
     */
    private function formatOrderDetails($order)
    {
        return [
            'orderId' => $order->reference_commande,
            'dateRegister' => $order->date,
            'status' => $order->status_orders,
            'depart_adresse' => $order->depart_adresse,
            'destination_adresse' => $order->destination_adresse,
            // 'numero_destinateur' => $order->numero_destinateur,
            // 'numero_destinataire' => $order->numero_destinataire,
            'libelle' => $order->libelle,
            'montant' => $order->montant,
            'engin' => $order->engin,
            'type_course' => $order->type_course,
            'status_payment' => $order->status_payment,
            'mode_payment' => $order->mode_payment,
            'instructions' => $order->instructions,
            'livreur' => $order->livreur ? [
                'nom' => $order->livreur->name,
                'numero_telephone' => $order->livreur->phone,
                'vehicle' => $order->livreur->vehicle
            ] : null
        ];
    }


    public function livreurOrders(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non authentifié'], 401);
        }

        if (!$user->livreur) {
            return response()->json(['success' => false, 'message' => 'Unauthorized - Pas livreur'], 403);
        }

        $orders = Order::where('livreur_id', $user->livreur->id)
            ->whereIn('status_orders', ['Acceptée', 'En cours', 'Annulée'])
            ->with(['user' => function($query) {
                $query->select('id', 'name as client_name', 'phone_number as client_phone');
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'client_name' => $order->user->client_name,
                    'client_phone' => $order->user->client_phone,
                    'numero_destinateur' => $order->numero_destinateur,
                    'numero_destinataire' => $order->numero_destinataire,
                    'montant' => $order->montant,
                    'depart_adresse' => $order->depart_adresse,
                    'destination_adresse' => $order->destination_adresse,
                    'depart_latitude' => $order->depart_lat,
                    'depart_longitude' => $order->depart_long,
                    'destination_latitude' => $order->destination_lat,
                    'destination_longitude' => $order->destination_long,
                    'status_orders' => $order->status_orders,
                    'created_at' => $order->created_at->format('H:i'),
                    'distance_km' => $order->distance_km,
                    'duree_minutes' => $order->duree_minutes,
                    'engin' => $order->engin,
                    'type_course' => $order->type_course,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
    public function cancelOrder(Request $request, $orderId)
    {
        try {
            Log::info("Tentative d'annulation de la commande ID: {$orderId}");

            // Chercher la commande
            $order = Order::find($orderId);

            if (!$order) {
                Log::warning("Commande non trouvée. ID: {$orderId}");
                return response()->json([
                    'message' => 'Commande non trouvée.',
                ], 404);
            }

            // Vérifier si la commande est déjà annulée
            if ($order->status_orders === 'Annulée') {
                Log::info("Commande déjà annulée. ID: {$orderId}");
                return response()->json([
                    'message' => 'Cette commande est déjà annulée.',
                ], 400);
            }

            // Mettre à jour le statut
            $order->status_orders = 'Annulée';
            $order->save();

            Log::info("Commande annulée avec succès. ID: {$orderId}");

            return response()->json([
                'message' => 'Commande annulée avec succès.',
            ], 200);

        } catch (\Exception $e) {
            Log::error("Erreur lors de l'annulation de la commande ID: {$orderId} - " . $e->getMessage());

            return response()->json([
                'message' => 'Erreur lors de l\'annulation de la commande.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getRecentOrders($userId)
{
    try {
        Log::info("[OrderController] Début getRecentOrders", ['user_id' => $userId]);

        $orders = Order::with(['user', 'livreur'])
            ->where('livreur_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'montant' => $order->montant,
                    'distance_km' => $order->distance_km,
                    'depart_adresse' => $order->depart_adresse,
                    'destination_adresse' => $order->destination_adresse,
                    'created_at' => $order->created_at->toDateTimeString(),
                    'user' => [
                        'name' => $order->user->name ?? null,
                        'image' => $order->user->image ?? null,
                        'rating' => $order->user->rating ?? null,
                    ],
                    'livreur' => $order->livreur ? [
                        'name' => $order->livreur->user->name ?? null,
                    ] : null,
                ];
            });

        Log::info("[OrderController] Commandes trouvées", ['count' => $orders->count()]);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    } catch (\Exception $e) {
        Log::error("[OrderController] Erreur", ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}

    public function updateStatus(Request $request, $orderId)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Acceptée', 'En cours', 'Terminée'])]
        ]);

        try {
            $order = Order::findOrFail($orderId);
            
            // Vérification de l'autorisation
            if ($order->livreur_id != auth()->user()->livreur->id) {
                return response()->json([
                    'message' => 'Action non autorisée'
                ], 403);
            }

            // Initialiser l'historique
            $history = [];
            if (!empty($order->historique_statut)) {
                // Si c'est une chaîne JSON, décoder
                if (is_string($order->historique_statut)) {
                    $history = json_decode($order->historique_statut, true) ?? [];
                } 
                // Si c'est déjà un tableau, utiliser directement
                elseif (is_array($order->historique_statut)) {
                    $history = $order->historique_statut;
                }
            }

            // Mettre à jour le statut
            $order->status_orders = $validated['status'];
            
            // Mettre à jour le statut du livreur
            $order->status_livreur = match($validated['status']) {
                'Acceptée' => 'En route',
                'En cours' => 'Arrivé',
                'Terminée' => 'Livré',
                default => $order->status_livreur
            };

            // Ajouter la nouvelle entrée à l'historique
            $history[] = [
                'status' => $validated['status'],
                'date' => now()->toDateTimeString(),
                'by' => 'livreur',
            ];

            // Sauvegarder l'historique
            $order->historique_statut = $history;
            
            $order->save();

            return response()->json([
                'message' => 'Statut mis à jour avec succès',
                'data' => $order
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur mise à jour statut: " . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la mise à jour',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function setOrderInProgress(Order $order)
{
    try {
        Log::info('Début de setOrderInProgress', ['order_id' => $order->id]);

        if ($order->status_orders !== 'Acceptée') {
            return response()->json([
                'success' => false,
                'message' => 'Commande non dans un état accepté pour démarrer.',
            ], 400);
        }

        $order->status_orders = 'En cours';
        $order->save();

        Log::info('Commande passée en cours', ['order_id' => $order->id]);

        return response()->json([
            'success' => true,
            'message' => 'Commande mise en cours avec succès.',
            'order' => $order,
        ]);
    } catch (\Exception $e) {
        Log::error('Erreur setOrderInProgress', [
            'order_id' => $order->id,
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erreur serveur lors de la mise en cours.',
        ], 500);
    }
}
}