<?php

namespace App\Http\Controllers;

use App\Models\DemandeLivreur;
use App\Models\Livreur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LivreurController extends Controller
{
    // Affiche la liste des livreurs
    public function index()
    {
        // Récupérer les utilisateurs avec le rôle "livreur"
        $livreurs = Livreur::whereHas('user', function ($query) {
            $query->whereHas('roles', function ($query) {
                $query->where('name', 'livreur'); // Vérifie que l'utilisateur a le rôle 'livreur'
            });
        })->get();
        

        return view('pages.livreurs.index', compact('livreurs'));
    }

    // Affiche le formulaire pour ajouter un livreur
   

    public function demandes()
    {
        $livreurs = DemandeLivreur::all();
        return view('pages.livreurs.demandes', compact('livreurs'));
    }

    public function refuser($id, Request $request)
{
    $livreur = DemandeLivreur::findOrFail($id);

    // Validation du motif de refus
    $validated = $request->validate([
        'message' => 'required|max:15',
    ]);

    // Mise à jour du statut du livreur et enregistrement du motif
    $livreur->approuve = 0;
    $livreur->message = $validated['message'];
    $livreur->save();

    // Rediriger avec un message de succès
    return redirect()->route('livreurs.index')->with('success', 'Livreur refusé avec succès.');
}

//     public function approuver($livreurId)
// {
    

//     // Trouver la demande de livreur par ID
//     $demandeLivreur = DemandeLivreur::findOrFail($livreurId);
    

//     // Vérifier si la demande a déjà été approuvée ou non
//     if ($demandeLivreur->approuve) {
//         return redirect()->back()->with('error', 'Cette demande a déjà été approuvée.');
//     }

//     // Mettre à jour le statut de la demande comme approuvée
//     $demandeLivreur->approuve = true;
//     $demandeLivreur->save();

    
//     $user = User::create([
//         'name' => $demandeLivreur->nom . ' ' . $demandeLivreur->prenoms, 
//         'email' => $demandeLivreur->email,  
//         'password' => Hash::make($demandeLivreur->numero_telephone),
//         'phone_number' => '225' . $demandeLivreur->numero_telephone,
//         'status' => 'Actif',
//     ]);
    
//     // Assigner le rôle "livreur" à l'utilisateur
//     $user->assignRole('livreur');
    
//     $livreur = Livreur::create([
//         'user_id' => $user->id,  
//         'code' => 'LIV_' . strtoupper(substr(preg_replace('/\D/', '', uniqid()), -4)),  
//         'nom' => $demandeLivreur->nom,
//         'prenoms' => $demandeLivreur->prenoms,
//         'numero_telephone' => '225' . $demandeLivreur->numero_telephone,
//         'lieu_residence' => $demandeLivreur->lieu_residence,
//         'informations_complementaires' => $demandeLivreur->informations_complementaires,
//         'type' => $demandeLivreur->type,  // Externe ou Interne
//         'status' => 'Actif',  
//     ]);

//     $demandeLivreur->delete();
    
//     // Retourner un message de succès
//     return redirect()->route('livreurs.demandes')->with('success', 'Le livreur a été créé avec succès et la demande a été supprimée.');
// }

public function approuver($livreurId)
{
    // Trouver la demande de livreur par ID
    $demandeLivreur = DemandeLivreur::findOrFail($livreurId);
    
    // Vérifier si la demande a déjà été approuvée ou non
    if ($demandeLivreur->approuve) {
        return redirect()->back()->with('error', 'Cette demande a déjà été approuvée.');
    }

    // Formater le numéro de téléphone
    $numeroTelephone = '225' . $demandeLivreur->numero_telephone;
    
    // Vérifier si le numéro existe déjà dans la table users ou livreurs
    $userExists = User::where('phone_number', $numeroTelephone)->exists();
    $livreurExists = Livreur::where('numero_telephone', $numeroTelephone)->exists();
    
    if ($userExists || $livreurExists) {
        return redirect()->back()->with('error', 'Ce numéro de téléphone est déjà utilisé par un autre utilisateur/livreur.');
    }

    // Mettre à jour le statut de la demande comme approuvée
    $demandeLivreur->approuve = true;
    $demandeLivreur->save();

    // Créer l'utilisateur
    $user = User::create([
        'name' => $demandeLivreur->nom . ' ' . $demandeLivreur->prenoms, 
        'email' => $demandeLivreur->email,  
        'password' => Hash::make($demandeLivreur->numero_telephone),
        'phone_number' => $numeroTelephone,
        'status' => 'Actif',
    ]);
    
    // Assigner le rôle "livreur" à l'utilisateur
    $user->assignRole('livreur');
    
    // Créer le livreur
    $livreur = Livreur::create([
        'user_id' => $user->id,  
        'code' => 'LIV_' . strtoupper(substr(preg_replace('/\D/', '', uniqid()), -4)),  
        'nom' => $demandeLivreur->nom,
        'prenoms' => $demandeLivreur->prenoms,
        'numero_telephone' => $numeroTelephone,
        'lieu_residence' => $demandeLivreur->lieu_residence,
        'informations_complementaires' => $demandeLivreur->informations_complementaires,
        'type' => $demandeLivreur->type,  // Externe ou Interne
        'status' => 'Actif',  
    ]);

    $demandeLivreur->delete();
    
    // Retourner un message de succès
    return redirect()->route('livreurs.demandes')->with('success', 'Le livreur a été créé avec succès et la demande a été supprimée.');
}
    

// public function store(Request $request)
// {
//     // Validation des données
//     $request->validate([
//         'nom' => 'required|string|max:255',
//         'prenoms' => 'required|string|max:255',
//         'numero_telephone' => 'required|string|max:10',
//         'lieu_residence' => 'required|string|max:255',
//         'a_moto' => 'required|boolean',
//         'type' => 'required|string',
//     ]);

//     // Traiter le numéro pour ajouter 225 s'il manque
//     $numero_telephone = $request->numero_telephone;
//     if (!str_starts_with($numero_telephone, '225')) {
//         $numero_telephone = '225' . $numero_telephone;
//     }

//     // Debug pour vérifier le numéro modifié
//     logger()->info('Numéro de téléphone après ajout du 225 : ' . $numero_telephone);

//     // Générer un code unique pour le livreur
//     $livreurCode = 'LIV_' . strtoupper(substr(preg_replace('/\D/', '', uniqid()), -4)); // Code unique pour le livreur
//     logger()->info('Code généré pour le livreur : ' . $livreurCode);

//     // Créer un utilisateur avec un mot de passe par défaut
//     $user = User::create([
//         'name' => $request->prenoms . ' ' . $request->nom,
//         'phone_number' => $numero_telephone,
//         'password' => Hash::make($numero_telephone),
//         'status' => "Actif",
//         'image' => 'storage/app/public/images/profile-default.webp', // Ajouter l'image par défaut
//     ]);

//     logger()->info('Utilisateur créé avec ID : ' . $user->id);

//     // Assigner le rôle "livreur"
//     $user->assignRole('livreur');
//     logger()->info('Rôle livreur assigné à l\'utilisateur');

//     // Créer le livreur
//     $livreur = Livreur::create([
//         'user_id' => $user->id,
//         'code' => $livreurCode,
//         'nom' => $request->nom,
//         'type' => $request->type,
//         'prenoms' => $request->prenoms,
//         'numero_telephone' => $numero_telephone,
//         'lieu_residence' => $request->lieu_residence,
//         'informations_complementaires' => $request->informations_complementaires,
//     ]);

//     logger()->info('Livreur créé avec ID : ' . $livreur->id);

//     return redirect()->route('livreurs.index')->with('success', 'Livreur créé avec succès');
// }

public function store(Request $request)
{
    // Validation des données
    $request->validate([
        'nom' => 'required|string|max:255',
        'prenoms' => 'required|string|max:255',
        'numero_telephone' => 'required|string|max:10',
        'lieu_residence' => 'required|string|max:255',
        'a_moto' => 'required|boolean',
        'type' => 'required|string',
    ]);

    // Traiter le numéro pour ajouter 225 s'il manque
    $numero_telephone = $request->numero_telephone;
    if (!str_starts_with($numero_telephone, '225')) {
        $numero_telephone = '225' . $numero_telephone;
    }

    // Vérifier si le numéro existe déjà
    $userExists = User::where('phone_number', $numero_telephone)->exists();
    $livreurExists = Livreur::where('numero_telephone', $numero_telephone)->exists();
    
    if ($userExists || $livreurExists) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Ce numéro de téléphone est déjà utilisé par un autre utilisateur/livreur.');
    }

    // Générer un code unique pour le livreur
    $livreurCode = 'LIV_' . strtoupper(substr(preg_replace('/\D/', '', uniqid()), -4));
    logger()->info('Code généré pour le livreur : ' . $livreurCode);

    // Créer un utilisateur avec un mot de passe par défaut
    $user = User::create([
        'name' => $request->prenoms . ' ' . $request->nom,
        'phone_number' => $numero_telephone,
        'password' => Hash::make($numero_telephone),
        'status' => "Actif",
        'image' => 'storage/app/public/images/profile-default.webp',
    ]);

    logger()->info('Utilisateur créé avec ID : ' . $user->id);

    // Assigner le rôle "livreur"
    $user->assignRole('livreur');
    logger()->info('Rôle livreur assigné à l\'utilisateur');

    // Créer le livreur
    $livreur = Livreur::create([
        'user_id' => $user->id,
        'code' => $livreurCode,
        'nom' => $request->nom,
        'type' => $request->type,
        'prenoms' => $request->prenoms,
        'numero_telephone' => $numero_telephone,
        'lieu_residence' => $request->lieu_residence,
        'informations_complementaires' => $request->informations_complementaires,
        'a_moto' => $request->a_moto,
    ]);

    logger()->info('Livreur créé avec ID : ' . $livreur->id);

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

        if ($user) {
            // Inverser le statut du livreur
            $user->status = $user->status == 'Actif' ? 'Inactif' : 'Actif';
            $user->save();
        }

        // Rediriger vers la page précédente avec un message de succès
        return redirect()->route('livreurs.index')->with('success', 'Statut mis à jour avec succès');
    }
}
