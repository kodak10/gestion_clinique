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

    

    public function fraisHospitalisation()
{
    return $this->belongsTo(FraisHospitalisation::class);
}
public function frais()
{
    return $this->belongsTo(FraisHospitalisation::class, 'frais_hospitalisation_id');
}

}
