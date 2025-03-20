<?php

namespace App\Models\SchoolManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    use HasFactory;

    protected $table = 'abonnements';

    protected $fillable = [
        'auto_ecole_id', 
        'type', 
        'montant', 
        'date_debut', 
        'date_fin', 
        'statut'
    ];

    protected $casts = [
        'statut' => 'boolean',
    ];

    public function autoEcole()
    {
        return $this->belongsTo(AutoEcole::class);
    }
}

