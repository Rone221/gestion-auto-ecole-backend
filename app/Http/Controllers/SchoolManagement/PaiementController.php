<?php

namespace App\Http\Controllers\SchoolManagement;

use App\Http\Controllers\Controller;
use App\Models\SchoolManagement\Paiement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaiementController extends Controller
{
    /**
     * Lister tous les paiements.
     */
    public function index(): JsonResponse
    {
        $paiements = Paiement::with('autoEcole')->latest()->get();
        return response()->json($paiements);
    }

    /**
     * Voir le détail d’un paiement.
     */
    public function show($id): JsonResponse
    {
        $paiement = Paiement::with('autoEcole')->find($id);

        if (!$paiement) {
            return response()->json(['message' => 'Paiement non trouvé'], 404);
        }

        return response()->json($paiement);
    }

    /**
     * Créer un paiement.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'auto_ecole_id' => 'required|exists:auto_ecoles,id',
            'montant' => 'required|numeric|min:0',
            'motif' => 'required|in:abonnement,pénalité,autre',
            'statut' => 'required|in:en_attente,réglé,en_retard,échoué',
            'methode_paiement' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'payable_jusqua' => 'nullable|date',
        ]);

        $paiement = Paiement::create($validated);
        return response()->json($paiement, 201);
    }

    /**
     * Mettre à jour un paiement.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $paiement = Paiement::find($id);

        if (!$paiement) {
            return response()->json(['message' => 'Paiement non trouvé'], 404);
        }

        $validated = $request->validate([
            'montant' => 'sometimes|numeric|min:0',
            'motif' => 'sometimes|in:abonnement,pénalité,autre',
            'statut' => 'sometimes|in:en_attente,réglé,en_retard,échoué',
            'methode_paiement' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'payable_jusqua' => 'nullable|date',
        ]);

        $paiement->update($validated);
        return response()->json($paiement);
    }

    /**
     * Supprimer un paiement.
     */
    public function destroy($id): JsonResponse
    {
        $paiement = Paiement::find($id);

        if (!$paiement) {
            return response()->json(['message' => 'Paiement non trouvé'], 404);
        }

        $paiement->delete();
        return response()->json(['message' => 'Paiement supprimé avec succès']);
    }
}
