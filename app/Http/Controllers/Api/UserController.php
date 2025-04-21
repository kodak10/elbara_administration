<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livreur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        // Mise à jour des infos de l'utilisateur
        $user->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ]);

        // Mise à jour aussi dans la table livreurs
        $livreur = Livreur::where('user_id', $user->id)->first();

        if ($livreur) {
            // Diviser le nom en nom + prénoms (si besoin)
            $fullName = explode(' ', $request->name, 2);
            $nom = $fullName[0];
            $prenoms = isset($fullName[1]) ? $fullName[1] : '';

            $livreur->update([
                'nom' => $nom,
                'prenoms' => $prenoms,
                'numero_telephone' => $request->phone_number,
            ]);
        }

        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'user' => $user,
        ]);
    }

    public function updateProfileImage(Request $request)
    {
        $user = Auth::user();
    
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:4092',
        ]);
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
    
            // ENREGISTRER SUR LE DISQUE PUBLIC
            $path = $image->storeAs('profile_images', $imageName, 'public');
    
            // On sauvegarde seulement le chemin relatif "profile_images/monimage.jpg"
            $user->image = $path;
            $user->save();
    
            // Mise à jour du livreur lié
            $livreur = Livreur::where('user_id', $user->id)->first();
            if ($livreur) {
                $livreur->photo = $path;
                $livreur->save();
            }
    
            return response()->json([
                'message' => 'Image de profil mise à jour avec succès',
                'user' => $user,
            ]);
        }
    
        return response()->json([
            'message' => 'Aucune image fournie',
        ], 400);
    }
    
    
}
