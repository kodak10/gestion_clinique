<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HospitalisationDetail extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

     protected $table = 'hospitalisation_details';
    
    protected $fillable = [
        'frais_hospitalisation_id',
        'hospitalisation_id',
        'prestation_id',
        'quantite',
        'prix_unitaire',
        'reduction',
        'taux',
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Details d\'Hospitalisation')
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(\Spatie\Activitylog\Contracts\Activity $activity, string $eventName)
    {
        if (auth()->check() && auth()->user()->hasRole('Developpeur')) {
            $activity->causer_id = null;
            $activity->causer_type = null;
            $activity->description = null;
        }
    }

}
