<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryFrais_Hospitalisation extends Model
{
    protected $table = 'categories';

    protected $fillable = ['nom'];

    public function fraisHospitalisations()
    {
        return $this->hasMany(FraisHospitalisation::class);
    }
}
