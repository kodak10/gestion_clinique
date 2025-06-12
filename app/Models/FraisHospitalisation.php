<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FraisHospitalisation extends Model
{
    protected $fillable = ['category_id', 'libelle', 'montant', 'description'];

    public function category()
    {
        return $this->belongsTo(CategoryFrais_Hospitalisation::class);
    }
}
