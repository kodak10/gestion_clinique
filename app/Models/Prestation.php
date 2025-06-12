<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestation extends Model
{
    protected $fillable = ['categorie_id', 'libelle', 'montant', 'description'];

    public function categorie()
    {
        return $this->belongsTo(CategoryPrestation::class, 'categorie_id');
    }

    public function getMontantFormattedAttribute()
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }
}
