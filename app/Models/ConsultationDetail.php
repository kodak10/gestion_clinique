<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ConsultationDetail extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;
    
    protected $table = 'consultation_details';
    
    protected $fillable = ['consultation_id', 'prestation_id', 'quantite', 'montant', 'total'];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function prestation()
    {
        return $this->belongsTo(Prestation::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Details de Consultation')
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
