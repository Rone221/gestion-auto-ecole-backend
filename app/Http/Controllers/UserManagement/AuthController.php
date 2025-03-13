<?php

namespace App\Http\Controllers;

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
                'role' => 'required|string|in:admin,moniteur,eleve,comptable',
            ], [
                'nom.required' => 'Le nom est requis.',
                'email.required' => 'L\'adresse email est requise.',
                'email.email' => 'L\'adresse email doit être valide.',
                'email.unique' => 'Un compte avec cette adresse email existe déjà.',
                'password.required' => 'Le mot de passe est requis.',
                'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
                'password.confirmed' => 'Les mots de passe ne correspondent pas.',
                'role.required' => 'Le rôle est requis.',
                'role.in' => 'Le rôle fourni est invalide.',
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

            // Vérifier et attribuer le rôle
            $role = Role::where('name', $validatedData['role'])->first();
            if (!$role) {
                return response()->json(['message' => "Le rôle '{$validatedData['role']}' n'existe pas"], 400);
            }

            $utilisateur->assignRole($role);

            return response()->json([
                'message' => 'Utilisateur créé avec succès',
                'utilisateur' => $utilisateur,
                'roles' => $utilisateur->getRoleNames(),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de l\'inscription.', 'error' => $e->getMessage()], 500);
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

            if (!Auth::attempt($validatedData)) {
                return response()->json(['message' => 'Email ou mot de passe incorrect'], 401);
            }

            $request->session()->regenerate(); // ✅ Regénère la session pour Sanctum

            return response()->json(['message' => 'Connexion réussie'], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la connexion.', 'error' => $e->getMessage()], 500);
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

            $user->tokens()->delete();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json(['message' => 'Déconnexion réussie'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la déconnexion.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupérer les informations de l'utilisateur connecté.
     */
    public function me(Request $request)
    {
        try {
            if (!$request->user()) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            return response()->json([
                'utilisateur' => $request->user(),
                'roles' => $request->user()->getRoleNames(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération du profil.', 'error' => $e->getMessage()], 500);
        }
    }
}
