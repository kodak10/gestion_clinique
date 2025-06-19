<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_depense_id',
        'numero_recu',
        'libelle',
        'montant',
        'date',
        'numero_cheque',
        'description'
    ];

    protected $casts = [
        'date' => 'date',
        'montant' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryDepense::class, 'category_depense_id');
    }
}
