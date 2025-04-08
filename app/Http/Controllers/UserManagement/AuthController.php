<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends Controller
{
    /**
     * Inscription d'un utilisateur avec attribution de rôle.
     */
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'nullable|string|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'telephone' => 'nullable|string|max:20',
                'adresse' => 'nullable|string|max:255',
                'photo_profil' => 'nullable|string',
                'role' => 'required|string|exists:roles,name', // ✅ Validation du rôle existant
            ], [
                'role.required' => 'Le rôle est requis.',
                'role.exists' => 'Le rôle spécifié n\'existe pas.',
            ]);

            // Création de l'utilisateur
            $utilisateur = \App\Models\Auth\User::create([
                'nom' => $validatedData['nom'],
                'prenom' => $validatedData['prenom'] ?? null,
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'telephone' => $validatedData['telephone'] ?? null,
                'adresse' => $validatedData['adresse'] ?? null,
                'photo_profil' => $validatedData['photo_profil'] ?? null,
            ]);

            // Attribution du rôle fourni
            $utilisateur->assignRole($validatedData['role']);

            return response()->json([
                'message' => 'Utilisateur créé avec succès',
                'utilisateur' => $utilisateur,
                'roles' => $utilisateur->getRoleNames(),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'inscription.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Connexion d'un utilisateur et génération du token API.
     */
    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ], [
                'email.required' => 'L\'adresse email est requise.',
                'email.email' => 'L\'adresse email doit être valide.',
                'password.required' => 'Le mot de passe est requis.',
            ]);

            $credentials = [
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
            ];

            // 1. Vérifier l'utilisateur
            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Email ou mot de passe incorrect'], 401);
            }

            $user = Auth::user();

            // 2. Regénérer la session pour les navigateurs
            if ($request->hasSession()) {
                $request->session()->regenerate();
            }
            // 3. Générer un token pour les clients mobiles ou API


            $token = $user->createToken('auth_token')->plainTextToken;



            return response()->json([
                'message' => 'Connexion réussie',
                'token' => $token,
                'utilisateur' => $user,
                'roles' => $user->getRoleNames(),
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la connexion.', 'error' => $e->getMessage(),], 500);
        }
    }


    /**
     * Déconnexion et révocation du token.
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            // Si une requête avec token a été faite (stateless)
            if ($request->bearerToken()) {
                $token = $user->currentAccessToken();
                if ($token instanceof \Laravel\Sanctum\PersonalAccessToken) {
                    $token->delete(); // ✅ OK : ici delete() est bien reconnu
                }
            }

            // Si c'est une requête via session (stateful)
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return response()->json(['message' => 'Déconnexion réussie'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la déconnexion.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Récupérer les informations de l'utilisateur connecté.
     */
    public function me(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            return response()->json([
                'utilisateur' => $user,
                'roles' => $user->getRoleNames(),
                'auth_type' => $request->bearerToken() ? 'token' : 'session'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération du profil.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
