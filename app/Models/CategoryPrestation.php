<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPrestation extends Model
{
    protected $table = 'category_prestations';

    protected $fillable = ['nom'];

    public function prestations()
    {
        return $this->hasMany(Prestation::class, 'categorie_id');
    }
}
