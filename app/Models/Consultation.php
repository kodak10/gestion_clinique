<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'patient_id', 'user_id', 'date_consultation', 'motif', 'diagnostic',
        'prescription', 'total', 'ticket_moderateur', 'reduction', 'montant_paye'
    ];

    protected $dates = ['date_consultation'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prestations()
    {
        return $this->belongsToMany(Prestation::class, 'consultation_prestations')
            ->withPivot('quantite', 'montant')
            ->withTimestamps();
    }
}
