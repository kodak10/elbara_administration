<?php

namespace App\Http\Controllers;

use App\Models\Livreur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LivreurController extends Controller
{
    // Affiche la liste des livreurs
    public function index()
    {
        // Récupérer les utilisateurs avec le rôle "livreur"
        $livreurs = Livreur::whereHas('user', function ($query) {
            $query->role('livreur'); // Filtrer les utilisateurs ayant le rôle "livreur"
        })->get();

        return view('pages.livreurs.index', compact('livreurs'));
    }

    // Affiche le formulaire pour ajouter un livreur
   

    public function demandes()
    {
        $livreurs = Livreur::all();
        return view('pages.livreurs.demandes', compact('livreurs'));
    }


    // Enregistre un nouveau livreur
   

    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'numero_telephone' => 'required|string|max:15',
            'lieu_residence' => 'required|string|max:255',
            'a_moto' => 'required|boolean',
            'type' => 'required|string',
        ]);
    
        // Générer un code unique pour le livreur
        $livreurCode = 'LIV' . strtoupper(uniqid());
    
        // Créer un utilisateur avec un mot de passe par défaut
        $user = User::create([
            'name' => $request->prenoms . ' ' . $request->nom, // Nom de l'utilisateur
            'email' => $request->email, // L'email
            'password' => Hash::make('Elbara2025'), // Mot de passe par défaut
            'status' => "actif",
        ]);
    
        // Assigner le rôle "livreur" (ou un rôle personnalisé pour les livreurs)
        $user->assignRole('livreur'); // Assurez-vous que le rôle 'livreur' existe
    
        // Créer le livreur
        $livreur = Livreur::create([
            'code' => $livreurCode,
            'nom' => $request->nom,
            'prenoms' => $request->prenoms,
            'numero_telephone' => $request->numero_telephone,
            'lieu_residence' => $request->lieu_residence,
            'informations_complementaires' => $request->informations_complementaires, // Ajout des informations complémentaires
        ]);
    
        return redirect()->route('livreurs.index')->with('success', 'Livreur créé avec succès');
    }
    
    public function show($id)
    {
        // Récupérer le livreur par son ID
        $livreur = Livreur::findOrFail($id);

        // Retourner la vue avec les informations du livreur
        return view('pages.orders.show', compact('livreur'));
    }

    // Affiche le formulaire pour modifier un livreur
    public function edit($id)
    {
        $livreur = Livreur::findOrFail($id);
        return view('livreurs.edit', compact('livreur'));
    }

    // Met à jour les informations d'un livreur
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'numero_telephone' => 'required|string|max:15',
            'lieu_residence' => 'required|string|max:255',
            'statut' => 'required|string|in:Actif,Inactif',
        ]);

        $livreur = Livreur::findOrFail($id);
        $livreur->update($request->all());

        return redirect()->route('livreurs.index')->with('success', 'Livreur mis à jour avec succès.');
    }

    // Supprime un livreur
   

    public function updateStatus($userId)
    {
       // Trouver l'utilisateur par son ID
        $user = User::findOrFail($userId);

        // Vérifier si l'utilisateur a un livreur associé
        $livreur = $user->livreur;

        if ($livreur) {
            // Inverser le statut du livreur
            $livreur->status = $livreur->status == 'actif' ? 'inactif' : 'actif';
            $livreur->save();
        }

        // Rediriger vers la page précédente avec un message de succès
        return redirect()->route('livreurs.index')->with('success', 'Statut mis à jour avec succès');
    }
}
