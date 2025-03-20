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
        return response()->json($abonnement, 201);
    }
}
