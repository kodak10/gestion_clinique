<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryDepense extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description'];

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }
}
