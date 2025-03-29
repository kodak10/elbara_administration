<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
{
    // $livreurs = User::whereHas('roles', function($query) {
    //     $query->where('name', 'livreur');  // Vérifier si l'utilisateur a le rôle 'livreur'
    // })->get();

    $livreurs = User::get();
    
    $orders = Order::query();

    // Filtre par statut
    if ($request->has('status') && $request->status) {
        $orders->where('status_orders', $request->status);
    }

    // Filtre par engin
    if ($request->has('engin') && $request->engin) {
        $orders->where('engin', $request->engin);
    }

    // Filtre par type de course
    if ($request->has('type_course') && $request->type_course) {
        $orders->where('type_course', $request->type_course);
    }

    $orders = $orders->paginate(10);

    return view('pages.orders.index', compact('orders', 'livreurs'));
}


    public function filtrerParStatut($statut)
    {
        $orders = Order::where('status_orders', $statut)->get();
        return view('pages.orders.index', compact('orders'));
    }

    /**
     * Afficher l'historique des commandes
     */
    public function historique()
    {
        $orders = Order::whereIn('status_orders', ['Livrée', 'Annulée', 'Échouée'])->get();
        return view('pages.orders.historique', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('pages.orders.show', compact('order'));
    }

    public function assign(Request $request, $id)
    {
        // Trouver la commande par ID
        $order = Order::findOrFail($id);

        // Affecter le livreur sélectionné à la commande
        $order->livreur_id = $request->livreur_id;
        $order->save();

        // Rediriger avec un message de succès
        return redirect()->route('orders.index')->with('success', 'Livreur affecté avec succès');
    }

    public function cancel($id)
    {
        // Trouver la commande par ID
        $order = Order::findOrFail($id);

        // Annuler la commande
        $order->status_orders = 'Annulée';
        $order->save();

        // Rediriger avec un message de succès
        return redirect()->route('orders.index')->with('success', 'Commande annulée avec succès');
    }
}
