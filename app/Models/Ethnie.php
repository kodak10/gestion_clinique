<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ethnie extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
