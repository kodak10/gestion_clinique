<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicament extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'nom',
        'description',
        'forme_galenique',
        'dosage',
        'unite_mesure',
        'prix_achat',
        'prix_vente',
        'stock',
        'stock_alerte',
        'date_peremption',
        'categorie_id',
        'fournisseur_id'
    ];

    protected $casts = [
        'date_peremption' => 'date',
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieMedicament::class);
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function hospitalisations(): BelongsToMany
    {
        return $this->belongsToMany(Hospitalisation::class, 'hospitalisation_medicament')
                    ->withPivot('quantite', 'prix_unitaire', 'taux', 'total', 'posologie')
                    ->withTimestamps();
    }
}
