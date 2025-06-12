<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationPrestation extends Model
{
    protected $table = 'consultation_prestations';
    
    protected $fillable = ['consultation_id', 'prestation_id', 'quantite', 'montant'];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function prestation()
    {
        return $this->belongsTo(Prestation::class);
    }
}
