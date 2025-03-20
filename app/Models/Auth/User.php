<?php

namespace App\Models\Auth;

use App\Models\SchoolManagement\AutoEcole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }


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
        'password', // ✅ Nécessaire pour Sanctum
        'telephone',
        'adresse',
        'photo_profil',
        'auto_ecole_id', // ✅ Ajouté pour gérer l'assignation à une auto-école
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
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // ✅ Sécurité renforcée avec hashing natif
        ];
    }

    /**
     * Relation : Un utilisateur appartient à une auto-école (sauf SuperAdmin).
     */
    public function autoEcole()
    {
        return $this->belongsTo(AutoEcole::class, 'auto_ecole_id');
    }
}
