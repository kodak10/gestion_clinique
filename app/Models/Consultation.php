<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\ConsultationDetail;

class Consultation extends Model
{
    protected $fillable = [
        'numero_recu','user_id', 'patient_id', 'medecin_id', 'total',
        'ticket_moderateur', 'reduction',  'montant_a_paye', 'montant_paye','reste_a_payer',
        'methode_paiement', 'date_consultation','pdf_path',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    public function prestations()
    {
        return $this->belongsToMany(Prestation::class, 'consultation_details')
            ->withPivot('quantite', 'montant', 'total')
            ->withTimestamps();
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($consultation) {
            // Génération du numéro de reçu (3 lettres + 3 chiffres)
            $letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
            $numbers = str_pad(Consultation::count() + 1, 3, '0', STR_PAD_LEFT);
            $consultation->numero_recu = $letters . $numbers;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
{
    return $this->hasMany(ConsultationDetail::class);
}
public function reglement()
{
    return $this->hasOne(Reglement::class);
}


protected $casts = [
    'date_consultation' => 'datetime',
];

    
}
