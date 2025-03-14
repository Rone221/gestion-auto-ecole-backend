<?php

namespace App\Http\Controllers\SchoolManagement;

use App\Http\Controllers\Controller;
use App\Models\SchoolManagement\AutoEcole;
use Illuminate\Http\Request;


class AutoEcoleController extends Controller
{
    public function index()
    {
        return response()->json(AutoEcole::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|unique:auto_ecoles',
            'adresse' => 'required|string',
            'telephone' => 'required|string|unique:auto_ecoles',
            'email' => 'required|string|email|unique:auto_ecoles',
            'statut' => 'boolean'
        ]);

        $autoEcole = AutoEcole::create($validated);
        return response()->json($autoEcole, 201);
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
