<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CategoryFrais_Hospitalisation extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;
    
    protected $table = 'category_frais__hospitalisations';

    protected $fillable = ['nom'];

    // public function fraisHospitalisations()
    // {
    //     return $this->hasMany(FraisHospitalisation::class, 'category_id');
    // }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Categorie des Frais Hospitalisation')
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
