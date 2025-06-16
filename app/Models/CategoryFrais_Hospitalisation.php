<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryFrais_Hospitalisation extends Model
{
    protected $table = 'category_frais__hospitalisations';

    protected $fillable = ['nom'];

    public function fraisHospitalisations()
    {
        return $this->hasMany(FraisHospitalisation::class);
    }
}
