<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HospitalisationExamen extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['hospitalisation_id', 'examen_id', 'quantite', 'prix', 'total'];

    public function hospitalisation()
    {
        return $this->belongsTo(Hospitalisation::class);
    }

    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Examen d\'Hospitalisation')
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
