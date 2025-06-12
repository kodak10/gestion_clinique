<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialite extends Model
{
    protected $fillable = ['nom', 'description'];

    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }

    
}
