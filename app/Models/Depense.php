<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Depense extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'category_depense_id',
        'numero_recu',
        'libelle',
        'montant',
        'date',
        'numero_cheque',
        'description'
    ];

    protected $casts = [
        'date' => 'date',
        'montant' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryDepense::class, 'category_depense_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Depenses')
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
