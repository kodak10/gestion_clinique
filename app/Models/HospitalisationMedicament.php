<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HospitalisationMedicament  extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['hospitalisation_id', 'medicament_id', 'quantite', 'prix_unitaire', 'taux', 'total'];

    public function hospitalisation()
    {
        return $this->belongsTo(Hospitalisation::class);
    }

    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Medicament d\'Hospitalisation')
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
