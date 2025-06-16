<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reglement extends Model
{
    protected $fillable = [
        'consultation_id',
        'user_id',
        'montant',
        'methode_paiement',
        'type',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
