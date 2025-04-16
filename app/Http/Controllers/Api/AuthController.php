<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function checkPhone(Request $request)
{
    Log::info('🔍 Début de la vérification du numéro de téléphone', [
        'données_reçues' => $request->all()
    ]);

    $request->validate([
        'phone' => 'required|string|min:8|max:15',
    ]);

    $phone = $request->phone;
    Log::info('📞 Numéro reçu après validation : ' . $phone);

    // Vérification du format du numéro
    if (!preg_match('/^[0-9]{8,15}$/', $phone)) {
        Log::warning('❌ Format de numéro invalide', [
            'phone' => $phone
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Format de numéro invalide',
        ], 400);
    }

    // Vérifier si le numéro existe en base
    $userExists = User::where('phone_number', $phone)->exists();
    Log::info('✅ Vérification existence : ', [
        'phone' => $phone,
        'exists' => $userExists
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Numéro vérifié',
        'exists' => $userExists, // 👈 correspond à ce que ton code Flutter attend
    ], 200);
}

    public function register(Request $request)
    {
        Log::channel('auth')->info('Début enregistrement utilisateur', [
            'phone' => $request->phone,
            'name' => $request->name,
            'email' => $request->email // Ajout du log pour l'email
        ]);

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20|unique:users,phone_number',
                'email' => 'required|string|email|max:255|unique:users,email'
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'phone_number' => $validated['phone'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['phone']), // Utilisation du phone comme mot de passe par défaut
                'api_token' => null,
            ]);

            Log::channel('auth')->info('Utilisateur créé', ['user_id' => $user->id]);

            // Assignation du rôle user avec Spatie
            $user->assignRole('user');
            Log::channel('auth')->info('Rôle user attribué', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone_number' => $user->phone_number,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames(),
                ],
                'token' => $user->createToken('auth_token')->plainTextToken,
                'message' => 'Inscription réussie'
            ], 201);

        } catch (ValidationException $e) {
            Log::channel('auth')->error('Erreur validation inscription', [
                'errors' => $e->errors(),
                'data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::channel('auth')->error('Erreur inscription', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'inscription'
            ], 500);
        }
    }

    public function sendOtp(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Générer un OTP (exemple: 6 chiffres)
            $otp = "123456";

            // Ici vous devriez implémenter l'envoi réel (SMS, email, etc.)
            // Pour le moment on log juste pour le débogage
            Log::channel('auth')->info('OTP généré', [
                'phone' => $request->phone,
                'otp' => $otp
            ]);

            return response()->json([
                'success' => true,
                'message' => 'OTP envoyé avec succès',
                'otp' => $otp // À retirer en production!
            ]);

        } catch (\Exception $e) {
            Log::channel('auth')->error('Erreur sendOtp', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'OTP'
            ], 500);
        }
    }

//     public function verifyOtp(Request $request)
// {
//     // Validation des données
//     $validator = Validator::make($request->all(), [
//         'phone' => 'required|string|max:20',
//         'otp' => 'required|string|size:6' // Supposant un OTP de 6 chiffres
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Validation error',
//             'errors' => $validator->errors()
//         ], 422);
//     }

//     try {
//         // ICI - Implémentez votre logique de vérification réelle
//         // Ceci est un exemple basique - À adapter selon votre système
        
//         // En développement, vous pourriez comparer avec l'OTP généré précédemment
//         // En production, utilisez un service de vérification d'OTP
        
//         $isOtpValid = true; // Remplacez par votre logique de validation
        
//         if ($isOtpValid) {
//             // Trouver ou créer l'utilisateur
//             $user = User::firstOrCreate(
//                 ['phone' => $request->phone],
//                 ['password' => Hash::make($request->phone)] // Mot de passe par défaut
//             );

//             // Créer un token d'authentification
//             $token = $user->createToken('auth_token')->plainTextToken;

//             return response()->json([
//                 'success' => true,
//                 'message' => 'OTP vérifié avec succès',
//                 'user' => $user,
//                 'token' => $token
//             ]);
//         }

//         return response()->json([
//             'success' => false,
//             'message' => 'OTP invalide'
//         ], 401);

//     } catch (\Exception $e) {
//         Log::channel('auth')->error('Erreur verifyOtp', [
//             'error' => $e->getMessage(),
//             'trace' => $e->getTraceAsString()
//         ]);
        
//         return response()->json([
//             'success' => false,
//             'message' => 'Erreur lors de la vérification'
//         ], 500);
//     }
// }
public function verifyOtp(Request $request)
{
    Log::info('[verifyOtp] Requête reçue', $request->all());

    // Validation des données
    $validator = Validator::make($request->all(), [
        'phone' => 'required|string|max:20',
        'otp' => 'required|string|size:6' // Supposant un OTP de 6 chiffres
    ]);

    if ($validator->fails()) {
        Log::warning('[verifyOtp] Échec de validation', [
            'errors' => $validator->errors()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        Log::debug('[verifyOtp] Début de vérification OTP', [
            'phone' => $request->phone,
            'otp' => $request->otp
        ]);

        // 💡 À remplacer par votre vraie logique de vérification d'OTP
        $isOtpValid = true;

        if ($isOtpValid) {
            

            $user = User::withTrashed()->where('phone_number', $request->phone)->first();

            if ($user && $user->trashed()) {
                $user->restore();
                Log::info("Utilisateur restauré (soft deleted) ID: {$user->id}");
            } elseif (!$user) {
                $user = User::create([
                    'name' => 'kodak',
                    'phone_number' => $request->phone,
                    'password' => Hash::make($request->phone),
                ]);
                Log::info("Nouvel utilisateur créé ID: {$user->id}");
            }

            

            $token = $user->createToken('auth_token')->plainTextToken;

            Log::info('[verifyOtp] OTP valide - Utilisateur connecté', [
                'user_id' => $user->id,
                'phone' => $user->phone
            ]);

            return response()->json([
                'success' => true,
                'message' => 'OTP vérifié avec succès',
                'user' => $user,
                'token' => $token
            ]);
        }

        Log::warning('[verifyOtp] OTP invalide', [
            'phone' => $request->phone,
            'otp_reçu' => $request->otp
        ]);

        return response()->json([
            'success' => false,
            'message' => 'OTP invalide'
        ], 401);

    } catch (\Exception $e) {
        Log::error('[verifyOtp] Erreur lors de la vérification', [
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la vérification'
        ], 500);
    }
}


    public function getUser(Request $request)
    {
        $user = $request->user();

        Log::channel('auth')->info('Récupération infos utilisateur connecté', [
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone_number' => $user->phone_number,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ]
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
    
        Log::info("Demande de suppression du compte reçue pour l'utilisateur ID: {$user->id}");
    
        try {
            // Soft delete
            $user->delete(); // Si vous utilisez SoftDeletes
            Log::info("Utilisateur ID: {$user->id} supprimé (soft delete)");
    
            // Suppression des tokens (déconnexion)
            $user->tokens()->delete();
            Log::info("Tokens de l'utilisateur ID: {$user->id} supprimés");
    
            return response()->json([
                'success' => true,
                'message' => 'Compte supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors de la suppression du compte utilisateur ID: {$user->id} - " . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression du compte'
            ], 500);
        }
    }
}
