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
        'date_entree',
        'date_sortie'
    ];

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
    public function frais()
    {
        return $this->hasMany(FraisHospitalisation::class);
    }

    public function fraisHospitalisations()
    {
        return $this->hasMany(FraisHospitalisation::class);
    }

    public function details()
    {
        return $this->hasManyThrough(
            HospitalisationDetail::class,
            FraisHospitalisation::class
        );
    }
}
