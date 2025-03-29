<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    // Récupérer les commandes en cours / en attente
    $orders = Order::whereIn('status_orders', ['En cours', 'En attente'])->get();

    // Récupérer les notifications (par exemple, les derniers événements)
    // $notifications = Notification::latest()->limit(5)->get();

    // Récupérer les transactions récentes (par exemple, les paiements)
    // $transactions = Transaction::latest()->limit(5)->get();

    // Passer les données à la vue
    return view('pages.index', compact('orders'));
}
}
