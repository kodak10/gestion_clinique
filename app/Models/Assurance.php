<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Assurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'taux',
        'phone_number',
        'email',
        'siege',
        'image'
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : asset('assets/avatars/default-avatar.png');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
