<?php

namespace App\Models;

use App\Models\ConsultationDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class Consultation extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;
    
    protected static $logName = 'consultation';
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'numero_recu','user_id', 'patient_id', 'medecin_id', 'total', 
        'ticket_moderateur', 'reduction',  'montant_a_paye', 'montant_paye','reste_a_payer',
        'date_consultation','pdf_path',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    public function prestations()
    {
        return $this->belongsToMany(Prestation::class, 'consultation_details')
            ->withPivot('quantite', 'montant', 'total')
            ->withTimestamps();
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
{
    return $this->hasMany(ConsultationDetail::class);
}
public function reglements()
{
    return $this->hasOne(Reglement::class);
}


protected $casts = [
    'date_consultation' => 'datetime',
];

public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Consultations')
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(\Spatie\Activitylog\Contracts\Activity $activity, string $eventName)
    {
        // Ne pas logger si c’est un Developpeur
        if (auth()->check() && auth()->user()->hasRole('Developpeur')) {
            $activity->causer_id = null;
            $activity->causer_type = null;
            return null; // Ou on peut return null ici
        }
    }

    
}
