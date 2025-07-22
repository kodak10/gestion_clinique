<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActesAmbulatoire extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'consultation_id',
        'patient_id',
        'medecin_id',
        'code_acte',
        'libelle',
        'description',
        'cout',
        'taux_couverture',
        'montant_patient',
        'montant_rembourse',
        'statut',
        'date_realisation',
        'observations'
    ];

    protected $casts = [
        'date_realisation' => 'datetime',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    public function getStatutColorAttribute()
    {
        return [
            'en_attente' => 'orange',
            'realise' => 'green',
            'annule' => 'red',
            'reporte' => 'blue',
        ][$this->statut] ?? 'gray';
    }
}
