<?php

namespace App\Http\Controllers;

use App\Models\VehiculeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleTypeController extends Controller
{
    public function list()
    {
        $vehicleTypes = VehiculeType::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'price_per_km' => $item->price_per_km,
                'additional_price' => $item->additional_price,
                'risk_price' => $item->risk_price,
                'image' => $item->image_path // Ou l'URL complète si nécessaire
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $vehicleTypes
        ]);
    }


    public function index()
    {
        $vehicles = VehiculeType::all();
        return view('pages.vehicules.index', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'price_per_km' => 'required|numeric|min:0',
            'additional_price' => 'required|numeric|min:0',
            'risk_price' => 'required|numeric|min:0',
            'image' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('vehicles', 'public');
        }

        VehiculeType::create($data);

        return redirect()->route('vehicles.index')->with('success', 'Véhicule ajouté avec succès');
    }

    public function update(Request $request, VehiculeType $vehicle)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'price_per_km' => 'required|numeric|min:0',
            'additional_price' => 'required|numeric|min:0',
            'risk_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($vehicle->image_path) {
                Storage::disk('public')->delete($vehicle->image_path);
            }
            $data['image_path'] = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle->update($data);

        return redirect()->route('vehicles.index')->with('success', 'Véhicule mis à jour avec succès');
    }

    public function destroy(VehiculeType $vehicle)
    {
        if ($vehicle->image_path) {
            Storage::disk('public')->delete($vehicle->image_path);
        }
        
        $vehicle->delete();
        
        return redirect()->route('vehicles.index')->with('success', 'Véhicule supprimé avec succès');
    }
}
