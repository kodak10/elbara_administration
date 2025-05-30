<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{

    private function sendSms($phone, $message)
{
    $apiKey = 'paoFFVLaaLRmSPXmVKPHmSFPA8LGVPjg';
    $apiToken = 'KiuI1743759041';
    $senderId = 'ELBARA';

    $url = "https://panel.smsing.app/smsAPI?sendsms" .
           "&apikey=" . urlencode($apiKey) .
           "&apitoken=" . urlencode($apiToken) .
           "&type=sms" .
           "&from=" . urlencode($senderId) .
           "&to=" . urlencode($phone) .
           "&text=" . urlencode($message);

    try {
        $response = Http::timeout(30)->get($url);
        
        if ($response->failed()) {
            throw new \Exception("Erreur HTTP: " . $response->status());
        }

        $result = $response->json();

        if (!isset($result['status'])) {
            throw new \Exception("Réponse API invalide");
        }

        if ($result['status'] === 'queued') {
            return [
                'success' => true,
                'message' => 'SMS envoyé avec succès',
                'group_id' => $result['group_id']
            ];
        }

        $errorMessage = $result['message'] ?? 'Erreur inconnue de l\'API SMS';
        throw new \Exception($errorMessage);

    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => 'Erreur d\'envoi SMS: ' . $e->getMessage()
        ];
    }
}

private function normalizePhoneNumber($phone)
{
    // Supprimer tous les caractères non numériques
    $cleaned = preg_replace('/[^0-9]/', '', $phone);

    // Si le numéro commence par 0, le convertir en format international (pour Côte d'Ivoire)
    if (preg_match('/^0/', $cleaned)) {
        return '225' . substr($cleaned, 1);
    }

    // Si le numéro n'a pas de préfixe pays, ajouter 225 (code Côte d'Ivoire)
    if (!preg_match('/^\+?\d{1,3}/', $cleaned)) {
        return '225' . $cleaned;
    }

    // Retirer le + s'il existe
    return ltrim($cleaned, '+');
}


    // public function checkPhone(Request $request)
    // {
    //     Log::info('🔍 Début de la vérification du numéro de téléphone', [
    //         'données_reçues' => $request->all()
    //     ]);

    //     $request->validate([
    //         'phone' => 'required|string|min:8|max:10',
    //     ]);

    //     $phone = '225' . $request->phone;

    //     Log::info('📞 Numéro reçu après validation : ' . $phone);


    //     $userExists = User::where('phone_number', $phone)->exists();
    //     Log::info('✅ Vérification existence : ', [
    //         'phone' => $phone,
    //         'exists' => $userExists
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Numéro vérifié',
    //         'exists' => $userExists,
    //     ], 200);
    // }

    public function checkPhone(Request $request)
{
    Log::info('🔍 Début de la vérification du numéro de téléphone', [
        'données_reçues' => $request->all()
    ]);

    $request->validate([
        'phone' => 'required|string|min:8|max:10',
        'account_type' => 'required|in:user,livreur', // <- ajoute cette ligne
    ]);

    $phone = '225' . $request->phone;
    $accountType = $request->account_type;

    Log::info('📞 Numéro reçu après validation : ' . $phone . ' | Type de compte : ' . $accountType);

    $userExists = User::where('phone_number', $phone)
        ->whereHas('roles', function ($query) use ($accountType) {
            $query->where('name', $accountType);
        })
        ->exists();

    Log::info('✅ Vérification existence : ', [
        'phone' => $phone,
        'type' => $accountType,
        'exists' => $userExists
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Numéro vérifié',
        'exists' => $userExists,
    ], 200);
}


  

//     public function sendOtp(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'phone' => 'required|string|max:10',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Numéro invalide',
//             'errors' => $validator->errors()
//         ], 422);
//     }

//     try {
//         $phone = '225' . $request->phone;
        
//         // OTP fixe pour le numéro de test
//         $otp = ($phone === '2250101010101') ? '123456' : str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
//         Log::channel('sms')->info('Envoi OTP initié', [
//             'phone' => $phone,
//             'otp' => $otp,
//             'is_test_number' => ($phone === '2250101010101')
//         ]);

//         OtpCode::updateOrCreate(
//             ['phone_number' => $phone],
//             ['code' => $otp, 'expires_at' => now()->addMinutes(10)]
//         );

//         // Ne pas envoyer de SMS pour le numéro de test
//         if ($phone !== '2250101010101') {
//             $message = "Code: $otp";
//             $smsResult = $this->sendSms($phone, $message);
            
//             if (!$smsResult['success']) {
//                 throw new \Exception($smsResult['message']);
//             }
//         }

//         return response()->json([
//             'success' => true,
//             'message' => $phone === '2250101010101' ? 'Code de test généré' : 'Code envoyé',
//             'otp' => $otp
//         ]);

//     } catch (\Exception $e) {
//         Log::channel('sms')->error('Erreur OTP', [
//             'phone' => $this->normalizePhoneNumber($request->phone),
//             'error' => $e->getMessage()
//         ]);
//         return response()->json([
//             'success' => false,
//             'message' => 'Erreur envoi'
//         ], 500);
//     }
// }


// public function resendOtp(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'phone' => 'required|string|max:10',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Numéro invalide',
//             'errors' => $validator->errors()
//         ], 422);
//     }

//     try {
//         $phone = '225' . $request->phone;
        
//         // Cas spécial pour le numéro de test
//         if ($phone === '2250101010101') {
//             $otp = '123456'; // OTP fixe pour le numéro de test
//             $expiresAt = now()->addMinutes(10);
            
//             Log::channel('sms')->info('Renvoi OTP test', [
//                 'phone' => $phone,
//                 'action' => 'otp_fixe_envoye'
//             ]);
            
//             OtpCode::updateOrCreate(
//                 ['phone_number' => $phone],
//                 ['code' => $otp, 'expires_at' => $expiresAt, 'verified' => false]
//             );
            
//             return response()->json([
//                 'success' => true,
//                 'message' => 'Code de test régénéré',
//                 'otp' => $otp // Retourné uniquement en développement
//             ]);
//         }

//         // Comportement normal pour les autres numéros
//         $existingOtp = OtpCode::where('phone_number', $phone)
//                             ->where('expires_at', '>', now())
//                             ->first();

//         if ($existingOtp) {
//             $otp = $existingOtp->code;
//             $expiresAt = $existingOtp->expires_at;
//             Log::channel('laravel')->debug('OTP existant réutilisé', ['phone' => $phone]);
//         } else {
//             $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
//             $expiresAt = now()->addMinutes(10);
            
//             OtpCode::updateOrCreate(
//                 ['phone_number' => $phone],
//                 ['code' => $otp, 'expires_at' => $expiresAt, 'verified' => false]
//             );
//             Log::channel('sms')->debug('Nouvel OTP généré', ['phone' => $phone]);
//         }

//         // Envoi SMS uniquement pour les numéros non-test
//         $message = "Code: $otp";
//         $smsResult = $this->sendSms($phone, $message);

//         if (!$smsResult['success']) {
//             throw new \Exception($smsResult['message']);
//         }

//         return response()->json([
//             'success' => true,
//             'message' => 'Code renvoyé',
//             'expires_in' => now()->diffInSeconds($expiresAt)
//         ]);

//     } catch (\Exception $e) {
//         Log::channel('sms')->error('Erreur renvoi OTP', [
//             'phone' => $request->phone,
//             'error' => $e->getMessage()
//         ]);
//         return response()->json([
//             'success' => false,
//             'message' => 'Erreur lors du renvoi'
//         ], 500);
//     }
// }

public function sendOtp(Request $request)
{
    $validator = Validator::make($request->all(), [
        'phone' => 'required|string|max:10',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Numéro invalide',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $phone = '225' . $request->phone;
        $isProduction = config('app.env') === 'production'; // Variable déterminant l'environnement
        
        // OTP fixe pour l'environnement de test OU pour le numéro de test spécifique
        $otp = (!$isProduction || $phone === '2250101010101') ? '123456' : str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        Log::channel('sms')->info('Envoi OTP initié', [
            'phone' => $phone,
            'otp' => $otp,
            'is_test_number' => ($phone === '2250101010101'),
            'environment' => config('app.env')
        ]);

        OtpCode::updateOrCreate(
            ['phone_number' => $phone],
            ['code' => $otp, 'expires_at' => now()->addMinutes(10)]
        );

        // Ne pas envoyer de SMS en environnement de test ou pour le numéro de test spécifique
        if ($isProduction && $phone !== '2250101010101') {
            $message = "Code: $otp";
            $smsResult = $this->sendSms($phone, $message);
            
            if (!$smsResult['success']) {
                throw new \Exception($smsResult['message']);
            }
        }

        return response()->json([
            'success' => true,
            'message' => (!$isProduction || $phone === '2250101010101') ? 'Code de test généré' : 'Code envoyé',
            'otp' => $otp
        ]);

    } catch (\Exception $e) {
        Log::channel('sms')->error('Erreur OTP', [
            'phone' => $this->normalizePhoneNumber($request->phone),
            'error' => $e->getMessage()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Erreur envoi'
        ], 500);
    }
}

public function resendOtp(Request $request)
{
    $validator = Validator::make($request->all(), [
        'phone' => 'required|string|max:10',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Numéro invalide',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $phone = '225' . $request->phone;
        $isProduction = config('app.env') === 'production'; // Variable déterminant l'environnement
        
        // Cas spécial pour l'environnement de test ou le numéro de test
        if (!$isProduction || $phone === '2250101010101') {
            $otp = '123456'; // OTP fixe pour le test
            $expiresAt = now()->addMinutes(10);
            
            Log::channel('sms')->info('Renvoi OTP test', [
                'phone' => $phone,
                'action' => 'otp_fixe_envoye',
                'environment' => config('app.env')
            ]);
            
            OtpCode::updateOrCreate(
                ['phone_number' => $phone],
                ['code' => $otp, 'expires_at' => $expiresAt, 'verified' => false]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Code de test régénéré',
                'otp' => $otp
            ]);
        }

        // Comportement normal pour la production
        $existingOtp = OtpCode::where('phone_number', $phone)
                            ->where('expires_at', '>', now())
                            ->first();

        if ($existingOtp) {
            $otp = $existingOtp->code;
            $expiresAt = $existingOtp->expires_at;
            Log::channel('laravel')->debug('OTP existant réutilisé', ['phone' => $phone]);
        } else {
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = now()->addMinutes(10);
            
            OtpCode::updateOrCreate(
                ['phone_number' => $phone],
                ['code' => $otp, 'expires_at' => $expiresAt, 'verified' => false]
            );
            Log::channel('sms')->debug('Nouvel OTP généré', ['phone' => $phone]);
        }

        // Envoi SMS uniquement en production
        $message = "Code: $otp";
        $smsResult = $this->sendSms($phone, $message);

        if (!$smsResult['success']) {
            throw new \Exception($smsResult['message']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Code renvoyé',
            'expires_in' => now()->diffInSeconds($expiresAt)
        ]);

    } catch (\Exception $e) {
        Log::channel('sms')->error('Erreur renvoi OTP', [
            'phone' => $request->phone,
            'error' => $e->getMessage()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du renvoi'
        ], 500);
    }
}

// public function verifyOtp(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'phone' => 'required|string|max:10',
//         'otp' => 'required|string|size:6'
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Données invalides',
//             'errors' => $validator->errors()
//         ], 422);
//     }

//     try {
//         $phone = '225' . $request->phone;
//         $otp = $request->otp;

//         // Vérification spéciale pour le numéro de test (à supprimer en production)
//         if ($phone === '2250101010101' && $otp === '123456') {
//             Log::channel('otp')->info('Validation OTP test réussie', ['phone' => $phone]);
            
//             // On vérifie d'abord si l'utilisateur existe
//             $user = User::where('phone_number', $phone)->first();
            
//             if (!$user) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Compte non trouvé, veuillez vous inscrire'
//                 ], 404);
//             }
            
//             $token = $user->createToken('auth_token')->plainTextToken;
            
//             return response()->json([
//                 'success' => true,
//                 'message' => 'Connexion test réussie',
//                 'user' => $user,
//                 'token' => $token
//             ]);
//         }

//         // Vérification normale pour les autres numéros
//         $otpRecord = OtpCode::where('phone_number', $phone)
//                           ->where('code', $otp)
//                           ->where('expires_at', '>', now())
//                           ->first();

//         if (!$otpRecord) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Code invalide ou expiré'
//             ], 401);
//         }

//         $otpRecord->update(['verified' => true]);
        
//         // On ne crée plus automatiquement l'utilisateur ici
//         $user = User::where('phone_number', $phone)->first();
        
//         if (!$user) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Compte non trouvé, veuillez vous inscrire'
//             ], 404);
//         }
        
//         $token = $user->createToken('auth_token')->plainTextToken;
        
//         return response()->json([
//             'success' => true,
//             'message' => 'Connexion réussie',
//             'user' => $user,
//             'token' => $token
//         ]);

//     } catch (\Exception $e) {
//         Log::channel('otp')->error('Erreur vérification', [
//             'phone' => $request->phone,
//             'error' => $e->getMessage()
//         ]);
//         return response()->json([
//             'success' => false,
//             'message' => 'Erreur vérification: ' . $e->getMessage()
//         ], 500);
//     }
// }

public function verifyOtp(Request $request)
{
    // Validation des entrées
    $validator = Validator::make($request->all(), [
        'phone' => ['required', 'string', 'size:10', 'regex:/^[0-9]+$/'],
        'otp' => ['required', 'string', 'size:6', 'regex:/^[0-9]+$/']
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Données invalides',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $phone = '225' . $request->phone;
        $otp = $request->otp;
        $isProduction = config('app.env') === 'production';
        $isTestNumber = $phone === '2250101010101';

        // Gestion des tentatives de vérification
        $attemptsKey = 'otp_attempts_' . $phone;
        $attempts = Cache::get($attemptsKey, 0);

        if ($attempts >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Trop de tentatives, veuillez réessayer dans 15 minutes'
            ], 429);
        }

        // Traitement spécial pour le numéro de test en environnement non-prod
        if (!$isProduction && $isTestNumber && $otp === '123456') {
            Log::channel('otp')->info('Validation OTP test réussie', ['phone' => $phone]);
            
            $user = User::where('phone_number', $phone)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Compte non trouvé, veuillez vous inscrire'
                ], 404);
            }
            
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'message' => 'Connexion test réussie',
                'user' => $user->only(['id', 'name', 'phone_number']),
                'token' => $token
            ]);
        }

        // Vérification normale en production
        $otpRecord = OtpCode::where('phone_number', $phone)
                          ->where('code', $otp)
                          ->where('expires_at', '>', now())
                          ->first();

        if (!$otpRecord) {
            // Incrémenter le compteur de tentatives
            Cache::put($attemptsKey, $attempts + 1, now()->addMinutes(15));
            
            return response()->json([
                'success' => false,
                'message' => 'Code invalide ou expiré',
                'attempts_remaining' => 3 - ($attempts + 1)
            ], 401);
        }

        // Réussite de la vérification
        $otpRecord->update(['verified' => true]);
        Cache::forget($attemptsKey); // Réinitialiser les tentatives
        
        $user = User::where('phone_number', $phone)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Compte non trouvé, veuillez vous inscrire'
            ], 404);
        }
        
        // Révoquer les anciens tokens (sécurité)
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        Log::channel('otp')->info('Connexion réussie', [
            'user_id' => $user->id,
            'phone' => $phone
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'user' => $user->only(['id', 'name', 'phone_number']),
            'token' => $token
        ]);

    } catch (\Exception $e) {
        Log::channel('otp')->error('Erreur vérification OTP', [
            'phone' => $request->phone ?? 'N/A',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Une erreur technique est survenue'
        ], 500);
    }
}
    
public function register(Request $request)
{
    Log::channel('auth')->info('Début enregistrement utilisateur', [
        'phone' => substr($request->phone, 0, 3) . '******', // Masquage partiel
        'name' => $request->name,
        'email' => substr($request->email, 0, 3) . '******' // Masquage partiel
    ]);

    // Vérification renforcée avant création
    $existingUser = User::where('phone_number', '225' . $request->phone)
                       ->orWhere('email', $request->email)
                       ->first();

    if ($existingUser) {
        Log::channel('auth')->warning('Tentative d\'enregistrement avec informations existantes', [
            'existing_id' => $existingUser->id
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Un utilisateur avec ces informations existe déjà'
        ], 409);
    }

    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u', // Unicode pour accents
            'phone' => [
                'required',
                'string',
                'max:10',
                'min:10',
                'regex:/^[0-9]+$/',
                function ($attribute, $value, $fail) {
                    if (User::where('phone_number', '225'.$value)->exists()) {
                        $fail('Ce numéro est déjà utilisé.');
                    }
                }
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (User::where('email', $value)->exists()) {
                        $fail('Cet email est déjà utilisé.');
                    }
                }
            ]
        ]);

        // Transaction pour sécurité
        DB::transaction(function () use ($validated, &$user) {
            $user = User::create([
                'name' => strip_tags($validated['name']),
                'phone_number' => '225' . $validated['phone'],
                'email' => strtolower($validated['email']),
                'password' => Hash::make('225' . $validated['phone']),
                'status' => 'Actif',
                'email_verified_at' => null, // À vérifier plus tard
            ]);

            $user->assignRole('user');
        });

        Log::channel('auth')->info('Utilisateur créé avec succès', ['user_id' => $user->id]);

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
            'errors' => $e->errors()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Erreur de validation',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        Log::channel('auth')->error('Erreur inscription', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'inscription: ' . $e->getMessage()
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
            $user->delete();
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
