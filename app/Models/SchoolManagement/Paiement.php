<?php

namespace App\Models\SchoolManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'auto_ecole_id',
        'montant',
        'motif',
        'statut',
        'methode_paiement',
        'reference',
        'payable_jusqua',
        'derniere_relance',
    ];

    public function autoEcole()
    {
        return $this->belongsTo(AutoEcole::class);
    }

    protected $casts = [
        'derniere_relance' => 'datetime',
        'payable_jusqua' => 'datetime',
    ];
    
}
