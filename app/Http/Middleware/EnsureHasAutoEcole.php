<?php

// app/Http/Middleware/EnsureHasAutoEcole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasAutoEcole
{
    /**
     * Gérer l'accès uniquement aux utilisateurs liés à une auto-école
     * ou aux super administrateurs.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
        }

        // Si l'utilisateur est un super admin, on laisse passer
        if ($user->hasRole('adminSaas')) {
            return $next($request);
        }

        // Si l'utilisateur est adminAutoEcole mais n’a pas d’auto-école liée
        if ($user->hasRole('adminAutoEcole') && !$user->auto_ecole_id) {
            return response()->json(['message' => 'Aucune auto-école liée à ce compte.'], 403);
        }

        // Si l'utilisateur a un rôle valide et une auto-école
        if ($user->hasRole('adminAutoEcole') && $user->auto_ecole_id) {
            return $next($request);
        }

        // Dans tous les autres cas
        return response()->json(['message' => 'Accès refusé.'], 403);
    }
}
