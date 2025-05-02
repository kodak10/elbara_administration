<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();  // Récupère toutes les compagnies
        return view('pages.compagnies.index', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'logo' => 'nullable|image',
            'additional_info' => 'nullable|string',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'logo' => $logoPath,
            'additional_info' => $request->additional_info,
        ]);

        return redirect()->route('companies.index')->with('success', 'Compagnie ajoutée avec succès.');
    }

    public function update(Request $request, Company $company)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:companies,email,'.$company->id,
        'logo' => 'nullable|image',
        'additional_info' => 'nullable|string',
    ]);

    // Gestion du logo
    $logoPath = $company->logo; // Conserver le logo existant par défaut
    
    if ($request->hasFile('logo')) {
        // Supprimer l'ancien logo s'il existe
        if ($company->logo && Storage::disk('public')->exists($company->logo)) {
            Storage::disk('public')->delete($company->logo);
        }
        
        // Stocker le nouveau logo
        $logoPath = $request->file('logo')->store('logos', 'public');
    }

    // Mise à jour de la compagnie
    $company->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'logo' => $logoPath,
        'additional_info' => $validated['additional_info'],
    ]);

    return redirect()->route('companies.index')->with('success', 'Compagnie mise à jour avec succès');
}

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Compagnie supprimée avec succès.');
    }
}
