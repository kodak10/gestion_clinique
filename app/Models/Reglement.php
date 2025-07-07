<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Reglement extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;
    
    // protected $fillable = [
    //     'hospitalisation_id',
    //     'consultation_id',
    //     'user_id',
    //     'montant',
    //     'methode_paiement',
    //     'type',
    // ];
    protected $fillable = [
        'hospitalisation_id',
        'consultation_id',
        'user_id',
        'montant',
        'methode_paiement',
        'type',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function hospitalisation()
    {
        return $this->belongsTo(Hospitalisation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Règlements')
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
