<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Specialite extends Model
{

    use HasFactory, LogsActivity, SoftDeletes;
    
    protected $fillable = ['nom', 'description'];

    public function medecins()
    {
        return $this->hasMany(Medecin::class);
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Specialites')
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
