<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
           
    
            // Validation des données
            $validated = $request->validate([
                'user_id' => 'required',

                'depart_lat' => 'required|numeric',
                'depart_long' => 'required|numeric',
                'destination_lat' => 'required|numeric',
                'destination_long' => 'required|numeric',

                'depart_adresse'=> 'required',
                'destination_adresse'=> 'required',

                'numero_destinateur' => 'required',
                'numero_destinataire' => 'required',


                'libelle' => 'nullable|string',
                'montant' => 'required|numeric',
                'distance_km' => 'required|numeric',

                'engin' => 'required',
                'mode_payement' => 'required',
                //'status_payement' => 'required',
                //'type_course' => 'required',


            ]);
    
            // Création de la commande
            $order = Order::create([
                //'user_id' => $request->user()->id, // Utilisation de l'ID de l'utilisateur authentifié
                'reference_commande' => '00' . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT),
                
                'date' => now(),
                ...$validated,
                'status' => 'En attente'
            ]);
    
            return response()->json([
                'success' => true,
                'order' => $order,
                'message' => 'Order created successfully'
            ]);
    
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Order creation error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Order creation failed'
            ], 500);
        }
    }
    

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
}
