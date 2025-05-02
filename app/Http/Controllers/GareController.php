<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Gare;
use Illuminate\Http\Request;

class GareController extends Controller
{
    public function index()
    {
        // Récupérer les gares avec les compagnies associées
        $gares = Gare::with('compagnies')->get();
        // Récupérer toutes les compagnies
        $compagnies = Company::all();

        return view('pages.gares.index', compact('gares', 'compagnies'));
    }

    public function store(Request $request)
    {
        //dd($request);

        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
            'contact_01' => 'required|string|max:255',
            'contact_02' => 'nullable|string|max:255',
            'localisation' => 'required|string|max:255',
            'informations_complementaires' => 'nullable|string',
            'compagnie_ids' => 'required'
        ]);


        // Créer une nouvelle gare
        $gare = Gare::create([
            'nom' => $request->nom,
            'contact_01' => $request->contact_01,
            'contact_02' => $request->contact_02,
            'localisation' => $request->localisation,
            'informations_complementaires' => $request->informations_complementaires
        ]);

        // Attacher les compagnies sélectionnées à la gare
        $gare->compagnies()->sync($request->compagnie_ids);

        // Rediriger avec un message de succès
        return redirect()->route('gares.index')->with('success', 'Gare ajoutée avec succès.');
    }

    public function update(Request $request, Gare $gare)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'contact_01' => 'nullable|string|max:20',
        'contact_02' => 'nullable|string|max:20',
        'localisation' => 'nullable|string',
        'compagnies' => 'nullable|',
        'compagnies.*' => 'exists:companies,id',
    ]);

    // Mise à jour des données de base
    $gare->update([
        'nom' => $validated['nom'],
        'contact_01' => $validated['contact_01'],
        'contact_02' => $validated['contact_02'],
        'localisation' => $validated['localisation'],
    ]);

    // Synchronisation des compagnies (pour la relation many-to-many)
    if ($request->has('compagnies')) {
        $gare->compagnies()->sync($validated['compagnies']);
    } else {
        $gare->compagnies()->detach();
    }

    return redirect()->route('gares.index')->with('success', 'Gare mise à jour avec succès');
}

    public function destroy($id)
    {
        // Trouver la gare à supprimer
        $gare = Gare::findOrFail($id);
        $gare->delete();

        // Rediriger avec un message de succès
        return redirect()->route('gares.index')->with('success', 'Gare supprimée avec succès.');
    }
}
