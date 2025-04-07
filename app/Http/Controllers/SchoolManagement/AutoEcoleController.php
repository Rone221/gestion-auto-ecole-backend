<?php

namespace App\Http\Controllers\SchoolManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolManagement\AutoEcole;
use Illuminate\Http\JsonResponse;

class AutoEcoleController extends Controller
{
    /**
     * Lister toutes les auto-écoles.
     */
    public function index(): JsonResponse
    {
        $autoEcoles = AutoEcole::all();
        return response()->json($autoEcoles);
    }

    /**
     * Voir les détails d’une auto-école.
     */
    public function show($id): JsonResponse
    {
        $autoEcole = AutoEcole::find($id);

        if (!$autoEcole) {
            return response()->json(['message' => 'Auto-école non trouvée'], 404);
        }

        return response()->json($autoEcole);
    }

    /**
     * Créer une nouvelle auto-école.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'responsable' => 'required|string|max:255',
            'nom' => 'required|string|max:255|unique:auto_ecoles,nom',
            'adresse' => 'required|string|max:500',
            'telephone' => 'required|string|max:20|unique:auto_ecoles,telephone',
            'email' => 'required|email|unique:auto_ecoles,email',
        ]);

        $autoEcole = AutoEcole::create($validated);

        return response()->json([
            'message' => 'Auto-école créée avec succès.',
            'data' => $autoEcole
        ], 201);
    }


    /**
     * Modifier une auto-école.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $autoEcole = AutoEcole::find($id);

        if (!$autoEcole) {
            return response()->json(['message' => 'Auto-école non trouvée'], 404);
        }

        $validated = $request->validate([
            'responsable' => 'string|max:255',
            'nom' => 'sometimes|string|max:255',
            'adresse' => 'sometimes|string|max:500',
            'telephone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|unique:auto_ecoles,email,' . $id,
            'statut' => 'sometimes|boolean',
        ]);

        $autoEcole->update($validated);
        return response()->json($autoEcole);
    }

    /**
     * Supprimer une auto-école.
     */
    public function destroy($id): JsonResponse
    {
        $autoEcole = AutoEcole::find($id);

        if (!$autoEcole) {
            return response()->json(['message' => 'Auto-école non trouvée'], 404);
        }

        $autoEcole->delete();
        return response()->json(['message' => 'Auto-école supprimée avec succès']);
    }

    public function toggleStatus($id)
    {
    $autoEcole = AutoEcole::find($id);

    if (!$autoEcole) {
        return response()->json(['message' => 'Auto-école non trouvée'], 404);
    }

    $autoEcole->statut = !$autoEcole->statut;
    $autoEcole->save();

    return response()->json([
        'message' => 'Statut mis à jour avec succès',
        'statut' => $autoEcole->statut ? 'actif' : 'inactif'
    ]);
    }

}
