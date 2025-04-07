<?php

namespace App\Http\Controllers\SchoolManagement;

use App\Http\Controllers\Controller;
use App\Models\SchoolManagement\Abonnement;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{
    public function index()
    {
        return response()->json(Abonnement::with('autoEcole')->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'auto_ecole_id' => 'required|exists:auto_ecoles,id',
            'type' => 'required|string',
            'montant' => 'required|numeric',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'statut' => 'boolean'
        ]);

        
        $abonnement = Abonnement::create($validated);
        $abonnement->load('autoEcole');
        return response()->json($abonnement, 201);

        

    }

    public function update(Request $request, $id)
    {
        $abonnement = Abonnement::findOrFail($id);

        $validated = $request->validate([
            'type' => 'sometimes|string',
            'montant' => 'sometimes|numeric',
            'date_debut' => 'sometimes|date',
            'date_fin' => 'sometimes|date|after:date_debut',
            'statut' => 'sometimes|boolean',
        ]);

        $abonnement->update($validated);
        $abonnement->load('autoEcole');

        return response()->json($abonnement, 200);
    }
    public function destroy($id)
    {
        $abonnement = Abonnement::findOrFail($id);
        $abonnement->delete();

        return response()->json(['message' => 'Abonnement supprimé avec succès'], 204);
    }

    public function filtrer(Request $request)
{
    $query = Abonnement::with('autoEcole');

    if ($request->has('statut')) {
        $query->where('statut', filter_var($request->statut, FILTER_VALIDATE_BOOLEAN));
    }

    if ($request->has('auto_ecole_id')) {
        $query->where('auto_ecole_id', $request->auto_ecole_id);
    }

    return response()->json($query->get(), 200);
}




}
