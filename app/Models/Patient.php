<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'num_dossier',
        'nom',
        'prenoms',
        'date_naissance',
        'domicile',
        'sexe',
        'profession_id',
        'ethnie_id',
        'religion',
        'groupe_rhesus',
        'electrophorese',
        'assurance_id',
        'taux_couverture',
        'matricule_assurance',
        'contact_urgence',
        'contact_patient',
        'photo',
        'pdf_path',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }

    public function ethnie()
    {
        return $this->belongsTo(Ethnie::class);
    }

    public function assurance()
    {
        return $this->belongsTo(Assurance::class);
    }

    // Validation rules
    public static function rules($id = null)
    {
        return [
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'domicile' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'profession' => 'nullable|string|max:255',
            'ethnie' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'groupe_rhesus' => 'nullable|string|max:10',
            'electrophorese' => 'nullable|string|max:255',
            'assurance_id' => 'nullable|exists:assurances,id',
            'taux_couverture' => 'required_if:assurance_id,!null|nullable|integer|min:0|max:100',
            'matricule_assurance' => 'required_if:assurance_id,!null|nullable|string|max:50',
            'contact_urgence' => 'nullable|string|max:255',
            'contact_patient' => 'string|max:20',
            'photo' => 'nullable|string|max:255',
            'envoye_par' => 'nullable|string|max:255',
        ];
    }
}
