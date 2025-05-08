<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class OrderAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $livreurId;

    public function __construct(Order $order, $livreurId)
    {
        $this->order = $order;
        $this->livreurId = $livreurId;

        Log::info('Événement OrderAssigned instancié', [
            'order_id' => $order->id,
            'livreur_id' => $livreurId
        ]);
    }


    public function broadcastOn()
    {
        $channel = 'livreur.' . $this->livreurId;
        Log::info('OrderAssigned diffusé sur le canal privé du livreur', ['channel' => $channel]);

        return new PrivateChannel($channel);
    }


    public function broadcastWith()
    {
        return [
            'order' => [
                'id' => $this->order->id,
                'status' => $this->order->status_orders,
                'livreur' => $this->order->user->nom ?? 'Non assigné',
                'date' => $this->order->updated_at->format('d/m/Y H:i'),
            ],
            'message' => 'Votre commande #' . $this->order->id . ' a été assignée à un livreur'
        ];
    }

    public function broadcastAs()
    {
        Log::info('Nom de l’événement OrderAssigned : order.assigned');
        return 'order.assigned';
    }
}
