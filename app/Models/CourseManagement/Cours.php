<?php

namespace App\Models\CourseManagement;

use App\Models\Auth\User;
use App\Models\SchoolManagement\AutoEcole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;

    protected $table = 'cours';

    protected $fillable = [
        'titre',
        'description',
        'type',
        'date_debut',
        'date_fin',
        'statut',
        'moniteur_id',
        'eleve_id',
        'auto_ecole_id',
    ];

    public function moniteur()
    {
        return $this->belongsTo(User::class, 'moniteur_id');
    }

    public function eleve()
    {
        return $this->belongsTo(User::class, 'eleve_id');
    }

    public function autoEcole()
    {
        return $this->belongsTo(AutoEcole::class, 'auto_ecole_id');
    }
}
