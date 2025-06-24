<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HospitalisationMedicament  extends Model
{
    protected $fillable = ['hospitalisation_id', 'medicament_id', 'quantite', 'prix_unitaire', 'taux', 'total'];

    public function hospitalisation()
    {
        return $this->belongsTo(Hospitalisation::class);
    }

    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }
}
