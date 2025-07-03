<?php

namespace App\Models;

use App\Models\Hospitalisation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Medicament extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'code',
        'nom',
        
        'unite_mesure',
        'prix_achat',
        'prix_vente',
        'stock',
        'stock_alerte',
        'date_peremption',
        // 'categorie_id',
        // 'fournisseur_id'
    ];

    protected $casts = [
        'date_peremption' => 'date',
    ];

   

    public function hospitalisations()
    {
        return $this->belongsToMany(Hospitalisation::class, 'hospitalisation_medicament')
                    ->withPivot('stock', 'total')
                    ->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Medicaments')
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
