<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medecin extends Model
{
    protected $fillable = ['matricule', 'nom_complet', 'telephone','specialite_id'];

    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }
    
}
