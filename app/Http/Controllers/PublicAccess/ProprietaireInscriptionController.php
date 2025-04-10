<?php

namespace App\Http\Controllers\PublicAccess;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Auth\User;
use App\Models\SchoolManagement\AutoEcole;
use Spatie\Permission\Models\Role;

class ProprietaireInscriptionController extends Controller
{
    /**
     * Inscription d'un propriétaire avec sa propre auto-école
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Infos utilisateur
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'photo_profil' => 'nullable|string',

            // Infos auto-école
            'ecole_nom' => 'required|string|max:255|unique:auto_ecoles,nom',
            'ecole_adresse' => 'required|string|max:500',
            'ecole_telephone' => 'required|string|max:20|unique:auto_ecoles,telephone',
            'ecole_email' => 'required|email|unique:auto_ecoles,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // 1. Créer l'auto-école
            $autoEcole = AutoEcole::create([
                'nom' => $request->ecole_nom,
                'adresse' => $request->ecole_adresse,
                'telephone' => $request->ecole_telephone,
                'email' => $request->ecole_email,
                'responsable' => $request->nom . ' ' . $request->prenom,
            ]);

            // 2. Créer l'utilisateur
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telephone' => $request->telephone,
                'adresse' => $request->adresse,
                'photo_profil' => $request->photo_profil,
                'auto_ecole_id' => $autoEcole->id,
            ]);

            // 3. Rôle : adminAutoEcole ou proprietaire
            $role = Role::where('name', 'adminAutoEcole')->first();
            if (!$role) {
                throw new \Exception("Le rôle 'adminAutoEcole' n'existe pas.");
            }
            $user->assignRole($role);

            // 4. Génération du token Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return response()->json([
                'message' => "Inscription réussie avec auto-école",
                'utilisateur' => $user,
                'auto_ecole' => $autoEcole,
                'roles' => $user->getRoleNames(),
                'auth_type' => 'token',
                'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return response()->json(['message' => 'Erreur lors de l’inscription.', 'error' => $e->getMessage()], 500);
        }
    }
}
