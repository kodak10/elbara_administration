<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livreur;
use App\Models\Marketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        // Mise à jour des infos de l'utilisateur
        $user->update([
            'name' => $request->name,
            'phone_number' => Str::start($request->phone_number, '225'),

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
                'numero_telephone' => Str::start($request->phone_number, '225'),


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
    
    public function cards()
    {
        $cards = Marketing::all()->map(function($card) {
            return [
                'id' => $card->id,
                'name' => $card->title,
                'image' => $card->image_path, // Chemin relatif dans storage
                // autres champs...
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $cards
        ]);
    }
    
}
