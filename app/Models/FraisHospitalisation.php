<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FraisHospitalisation extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['libelle', 'montant', 'description'];

   

   

    // public function category()
    // {
    //     return $this->belongsTo(CategoryFrais_Hospitalisation::class, 'category_id');
    // }

    public function details()
    {
        return $this->hasMany(HospitalisationDetail::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Faris d\'Hospitalisation')
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
