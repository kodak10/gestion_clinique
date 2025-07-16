<?php

namespace App\Models;

use App\Models\Examen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Hospitalisation extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'user_id', 
        'medecin_id',
        'total',
        'ticket_moderateur',
        'caution',
        'reduction',
        'reduction_par',
        'montant_a_paye',
        'montant_paye',
        'reste_a_payer',
        'date_entree',
        'date_sortie',
        'facture_path'
        
    ];
    
    public function hospitalisation()
    {
        return $this->belongsTo(Hospitalisation::class);
    }

    public function frais()
    {
        return $this->belongsTo(FraisHospitalisation::class, 'frais_hospitalisation_id');
    }
    public function details()
    {
        return $this->hasMany(HospitalisationDetail::class);
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
    return $this->hasManyThrough(FraisHospitalisation::class, HospitalisationDetail::class, 'hospitalisation_id', 'id', 'id', 'frais_hospitalisation_id');
}

    public function fraisPharmacie()
    {
        return $this->hasMany(HospitalisationDetail::class)->whereHas('frais', function($q) {
            $q->where('category_id', 2); // ID de la catégorie Pharmacie
        });
    }
    
    

    public function medicaments()
    {
        return $this->belongsToMany(Medicament::class, 'hospitalisation_medicament')
                   ->withPivot('quantite', 'prix_unitaire', 'total',)
                   ->withTimestamps();
    }

    public function examens()
    {
        return $this->belongsToMany(Examen::class, 'hospitalisation_examen')
                   ->withPivot('quantite', 'prix', 'taux', 'total',)
                   ->withTimestamps();
    }

    public function reglements()
    {
        return $this->hasMany(Reglement::class);
    }

    protected $casts = [
        'date_entree' => 'datetime',
        'date_sortie' => 'datetime',
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Hospitalisations')
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(\Spatie\Activitylog\Contracts\Activity $activity, string $eventName)
    {
        if (auth()->check() && auth()->user()->hasRole('Developpeur')) {
            $activity->causer_id = null;
            $activity->causer_type = null;
            return null;
        }
    }

}
