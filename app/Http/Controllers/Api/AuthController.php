<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function checkPhone(Request $request)
    {
        Log::channel('auth')->info('Début vérification numéro', ['phone' => $request->phone]);

        try {
            $request->validate(['phone' => 'required|string|max:20']);

            $exists = User::where('phone_number', $request->phone)->exists();

            Log::channel('auth')->info('Résultat vérification numéro', [
                'phone' => $request->phone,
                'exists' => $exists
            ]);

            return response()->json([
                'success' => true,
                'exists' => $exists,
                'message' => $exists ? 'User exists' : 'New user'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('auth')->error('Erreur validation numéro', [
                'error' => $e->errors(),
                'phone' => $request->phone
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone format',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::channel('auth')->error('Erreur vérification numéro', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'phone' => $request->phone
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }

    public function register(Request $request)
    {
        Log::channel('auth')->info('Début enregistrement utilisateur', [
            'phone' => $request->phone,
            'name' => $request->name
        ]);

        try {
            $request->validate([
                'phone' => 'required|string|max:20|unique:users,phone_number',
                'name' => 'required|string|max:255'
            ]);

            $user = User::create([
                'name' => $request->name,
                'phone_number' => $request->phone,
                'password' => Hash::make($request->phone),
                'api_token' => null,
            ]);

            Log::channel('auth')->info('Utilisateur créé', ['user_id' => $user->id]);

            $user->assignRole('user');
            Log::channel('auth')->info('Rôle attribué', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone_number' => $user->phone_number,
                    'roles' => $user->getRoleNames(),
                    'permissions' => $user->getAllPermissions()->pluck('name'),
                ],
                'message' => 'Registration successful'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('auth')->error('Erreur validation inscription', [
                'errors' => $e->errors(),
                'data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
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
                'message' => 'Registration failed'
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|string|max:20',
                'otp' => 'required|string|size:6'
            ]);

            Log::channel('auth')->debug('OTP reçu', ['otp' => $request->otp]);

            if ($request->otp !== '123456') {
                Log::channel('auth')->warning('OTP invalide', [
                    'phone' => $request->phone,
                    'otp_reçu' => $request->otp
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Code OTP invalide'
                ], 401);
            }

            $user = User::where('phone_number', $request->phone)->first();

            if (!$user) {
                Log::channel('auth')->error('Utilisateur non trouvé pour OTP', [
                    'phone' => $request->phone
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone_number' => $user->phone_number,
                    'roles' => $user->getRoleNames(), // Spatie
                    'permissions' => $user->getPermissionNames(), // Spatie
                ],
                'message' => 'Connexion réussie'
            ]);

        } catch (\Exception $e) {
            Log::channel('auth')->error('Erreur vérification OTP', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur de vérification'
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

    public function logout(Request $request)
    {
        $user = $request->user();
        Log::channel('auth')->info('Déconnexion utilisateur', [
            'user_id' => $user->id,
            'phone' => $user->phone_number,
        ]);

        $user->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ]);
    }
}
