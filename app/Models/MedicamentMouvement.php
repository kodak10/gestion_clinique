<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicamentMouvement extends Model
{
    protected $table = 'medicament_mouvements';
    
    protected $fillable = [
        'medicament_id',
        'quantite',
        'type',
        'user_id',
        'hospitalisation_id'
    ];

    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hospitalisation()
    {
        return $this->belongsTo(Hospitalisation::class);
    }
}
