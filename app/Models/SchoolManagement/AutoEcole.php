<?php

namespace App\Models\SchoolManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoEcole extends Model
{
    use HasFactory;

    protected $table = 'auto_ecoles';

    protected $fillable = [
        'responsable',
        'nom', 
        'adresse', 
        'telephone', 
        'email', 
        'statut'
    ];

    public function abonnements()
    {
    return $this->hasMany(Abonnement::class);
    }

}



