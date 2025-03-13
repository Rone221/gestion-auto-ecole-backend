<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Les attributs qui peuvent être remplis en masse.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password', // Correction ici (obligatoire pour Sanctum)
        'telephone',
        'adresse',
        'photo_profil',
    ];

    /**
     * Les attributs qui doivent être masqués pour la sérialisation.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verifie_a' => 'datetime',
            'password' => 'hashed', // Correction ici
        ];
    }
}
