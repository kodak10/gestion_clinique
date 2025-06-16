<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationPrestation extends Model
{
    protected $table = 'consultation_details';
    
    protected $fillable = ['consultation_id', 'prestation_id', 'quantite', 'montant', 'total'];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function prestation()
    {
        return $this->belongsTo(Prestation::class);
    }
}
