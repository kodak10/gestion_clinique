<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Prestation extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected static $logName = 'Prestation Accueil';
    protected static $logOnlyDirty = true;
    
    protected $fillable = ['categorie_id', 'libelle', 'montant', 'description'];

    public function categorie()
    {
        return $this->belongsTo(CategoryPrestation::class, 'categorie_id');
    }

    public function getMontantFormattedAttribute()
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Prestation Accueil')
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
