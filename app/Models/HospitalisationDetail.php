<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HospitalisationDetail extends Model
{
     protected $table = 'hospitalisation_details';
    
    protected $fillable = [
        'frais_hospitalisation_id',
        'hospitalisation_id',
        'prestation_id',
        'quantite',
        'prix_unitaire',
        'reduction',

        'total'
    ];

    public function fraisHospitalisation(): BelongsTo
    {
        return $this->belongsTo(FraisHospitalisation::class);
    }

    public function prestation(): BelongsTo
    {
        return $this->belongsTo(Prestation::class);
    }
}
