<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    public function getClientInfo(Request $request)
{
    Log::info('Début de getClientInfo');
    
    try {
        $user = $request->user();
        Log::info('Utilisateur trouvé:', ['user_id' => $user->id]);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'nom' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone_number,
            ]
        ]);
        
    } catch (\Exception $e) {
        Log::error('Erreur dans getClientInfo: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Server error',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Récupère les commandes de l'utilisateur authentifié
     */
    public function getUserOrders(Request $request)
{
    $user = $request->user();
    Log::info('Utilisateur authentifié :', ['id' => $user->id, 'name' => $user->name ?? null]);

    $ordersQuery = Order::where('user_id', $user->id)
        ->with(['livreur' => function($query) {
            $query->select('id', 'nom', 'prenoms', 'photo');
        }])
        ->orderBy('created_at', 'desc');

    Log::info('Requête de commandes SQL : ' . $ordersQuery->toSql(), $ordersQuery->getBindings());

    $orders = $ordersQuery->get()->map(function ($order) {
        Log::info('Commande récupérée :', ['id' => $order->id, 'référence' => $order->reference_commande]);

        return [
            'id' => $order->id,
            'reference_commande' => $order->reference_commande,
            'depart_adresse' => $order->depart_adresse,
            'destination_adresse' => $order->destination_adresse,
            'montant' => $order->montant,
            'created_at' => $order->created_at->toIso8601String(),
            'status_orders' => $order->status_orders,
            'engin' => $order->engin,
            'livreur' => $order->livreur ? [
                'id' => $order->livreur->id,
                'nom' => $order->livreur->nom,
                'prenoms' => $order->livreur->prenoms,
                'photo' => $order->livreur->photo ? asset('storage/'.$order->livreur->photo) : null
            ] : null
        ];
    });

    Log::info('Nombre total de commandes retournées : ' . $orders->count());

    return response()->json([
        'success' => true,
        'orders' => $orders
    ]);
}

    /**
     * Récupère les détails d'une commande spécifique
     */
    public function getOrderDetails($id)
    {
        $order = Order::with(['livreur', 'user'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'reference_commande' => $order->reference_commande,
                'depart_adresse' => $order->depart_adresse,
                'destination_adresse' => $order->destination_adresse,
                'montant' => $order->montant,
                'date' => $order->created_at->format('d/m/Y'),
                'time' => $order->created_at->format('H:i'),
                'status_orders' => $order->status_orders,
                'engin' => $order->engin,
                'type_course' => $order->type_course,
                'status_payment' => $order->status_payment,
                'mode_payment' => $order->mode_payment,
                'instructions' => $order->instructions,
                'livreur' => $order->livreur,
                'numero_destinateur' => $order->numero_destinateur,
                'numero_destinataire' => $order->numero_destinataire,
                'libelle' => $order->libelle,
            ]
        ]);
    }

    /**
     * Annule une commande
     */
    public function cancelOrder(Request $request, $id)
    {
        $order = Order::where('user_id', $request->user()->id)
            ->findOrFail($id);

        if (!in_array($order->status_orders, ['En attente', 'Assignée', 'Acceptée'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande ne peut plus être annulée'
            ], 400);
        }

        $order->update([
            'status_orders' => 'Annulée',
            'historique_statut' => array_merge(
                $order->historique_statut ?? [],
                [['status' => 'Annulée', 'date' => now()->toDateTimeString()]]
            )
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Commande annulée avec succès'
        ]);
    }

}
