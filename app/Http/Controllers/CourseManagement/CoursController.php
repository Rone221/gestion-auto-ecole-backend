<?php

namespace App\Http\Controllers\CourseManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCoursRequest;
use App\Http\Requests\UpdateCoursRequest;
use App\Models\CourseManagement\Cours;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class CoursController extends Controller
{
    const STATUTS = ['planifie', 'reserve', 'termine', 'annule'];

    /**
     * Liste des cours d'une auto-école
     */
    public function index(Request $request)
    {
        $cours = Cours::where('auto_ecole_id', $request->user()->auto_ecole_id)
            ->with(['moniteur', 'eleve'])
            ->latest()
            ->paginate(10);

        return response()->json($cours);
    }

    /**
     * Créer un cours (par AdminAutoEcole ou Moniteur)
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validated();
        $data['auto_ecole_id'] = $request->user()->auto_ecole_id;

        $cours = Cours::create($data);

        return response()->json([
            'message' => 'Cours créé avec succès',
            'cours' => $cours
        ], 201);
    }


    /**
     * Voir un cours
     */
    public function show(Cours $cour)
    {
        return response()->json($cour->load(['moniteur', 'eleve']));
    }

    /**
     * Modifier un cours
     */
    public function update(UpdateCoursRequest $request, Cours $cour)
    {
        if (in_array($cour->statut, ['termine', 'annule'])) {
            return response()->json(['message' => 'Impossible de modifier un cours terminé ou annulé'], 403);
        }

        $cour->update($request->validated());

        return response()->json(['message' => 'Cours mis à jour avec succès', 'cours' => $cour]);
    }

    /**
     * Supprimer un cours
     */
    public function destroy(Cours $cour)
    {
        $cour->delete();

        return response()->json(['message' => 'Cours supprimé avec succès']);
    }

    /**
     * Réserver un cours (côté élève)
     */
    public function reserve(Request $request, Cours $cour)
    {
        if ($cour->statut != 'planifie' || $cour->eleve_id) {
            return response()->json(['message' => 'Cours non disponible à la réservation'], 403);
        }

        $cour->update([
            'eleve_id' => $request->user()->id,
            'statut' => 'reserve',
        ]);

        return response()->json(['message' => 'Réservation effectuée avec succès', 'cours' => $cour]);
    }

    /**
     * Annuler une réservation (Eleve ou Admin)
     */
    public function cancel(Request $request, Cours $cour)
    {
        if ($cour->statut != 'reserve') {
            return response()->json(['message' => 'Cours non réservé ou déjà annulé'], 403);
        }

        // Si ce n'est pas l'élève qui avait réservé
        if ($cour->eleve_id != $request->user()->id && !$request->user()->hasRole('adminAutoEcole')) {
            return response()->json(['message' => 'Non autorisé à annuler cette réservation'], 403);
        }

        $cour->update([
            'eleve_id' => null,
            'statut' => 'planifie',
        ]);

        return response()->json(['message' => 'Réservation annulée', 'cours' => $cour]);
    }

    /**
     * Terminer un cours (Moniteur ou Admin)
     */
    public function finish(Request $request, Cours $cour)
    {
        if ($cour->statut != 'reserve') {
            return response()->json(['message' => 'Cours non en cours de réservation'], 403);
        }

        $cour->update(['statut' => 'termine']);

        return response()->json(['message' => 'Cours terminé avec succès', 'cours' => $cour]);
    }

    /**
     * Annuler définitivement un cours (Admin)
     */
    public function cancelDefinitive(Request $request, Cours $cour)
    {
        $cour->update(['statut' => 'annule']);

        return response()->json(['message' => 'Cours annulé définitivement', 'cours' => $cour]);
    }
}
