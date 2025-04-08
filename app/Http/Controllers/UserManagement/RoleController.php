<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Retourne la liste des rôles disponibles.
     */
    public function index()
    {
        $roles = Role::select('id', 'name')->get();

        return response()->json([
            'roles' => $roles
        ]);
    }
}
