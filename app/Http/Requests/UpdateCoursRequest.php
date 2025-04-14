<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        // On laisse passer car déjà protégé via middleware
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'type' => 'sometimes|in:theorique,pratique',
            'date_debut' => 'sometimes|date|after:now',
            'date_fin' => 'sometimes|date|after:date_debut',
            'moniteur_id' => 'sometimes|exists:users,id',
        ];
    }
}
