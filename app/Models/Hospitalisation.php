<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospitalisation extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id', 
        'medecin_id',
        'total',
        'ticket_moderateur',
        'reduction',
        'montant_a_paye',
        'reste_a_payer',
        'date_entree',
        'date_sortie',
        
    ];
    
    public function hospitalisation()
    {
        return $this->belongsTo(Hospitalisation::class);
    }

    public function frais()
    {
        return $this->belongsTo(FraisHospitalisation::class, 'frais_hospitalisation_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }
    

    public function fraisHospitalisations()
    {
        return $this->hasMany(FraisHospitalisation::class);
    }
    public function fraisPharmacie()
    {
        return $this->hasMany(HospitalisationDetail::class)->whereHas('frais', function($q) {
            $q->where('category_id', 2); // ID de la catégorie Pharmacie
        });
    }

    public function details()
{
    return $this->hasMany(HospitalisationDetail::class);
}

}
