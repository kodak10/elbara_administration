<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partenaire;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartenaireController extends Controller
{
    // Afficher la liste des partenaires
    public function index()
    {
        $partenaires = Partenaire::latest()->get();
        return view('pages.partenaires.index', compact('partenaires'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        return view('pages.partenaires.create');
    }

    // Enregistrer un partenaire
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('partenaires', 'public');
        }

        Partenaire::create([
            'name' => $request->name,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'logo_url' => $logoPath,
        ]);

        return redirect()->route('partenaires.index')
            ->with('success', 'Partenaire créé avec succès.');
    }

    // Afficher le formulaire d'édition
    public function edit(Partenaire $partenaire)
    {
        return view('pages.partenaires.edit', compact('partenaire'));
    }

    // Mettre à jour un partenaire
    public function update(Request $request, Partenaire $partenaire)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $logoPath = $partenaire->logo_url;

        if ($request->hasFile('logo')) {
            // Supprimer l'ancienne image si elle existe
            if ($partenaire->logo_url) {
                Storage::disk('public')->delete($partenaire->logo_url);
            }
            $logoPath = $request->file('logo')->store('partenaires', 'public');
        }

        $partenaire->update([
            'name' => $request->name,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'logo_url' => $logoPath,
        ]);

        return redirect()->route('partenaires.index')
            ->with('success', 'Partenaire mis à jour avec succès.');
    }

    // Supprimer un partenaire
    public function destroy(Partenaire $partenaire)
    {
        if ($partenaire->logo_url) {
            Storage::disk('public')->delete($partenaire->logo_url);
        }

        $partenaire->delete();

        return redirect()->route('partenaires.index')
            ->with('success', 'Partenaire supprimé avec succès.');
    }
}