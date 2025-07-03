<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity, SoftDeletes;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
   use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'pseudo', 
        'phone_number',
        'password',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function findForPassport($username)
    {
        return $this->where('pseudo', $username)->first();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }




  // Méthode pour spécifier les options d'enregistrement des activités
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable) // Définit les attributs à loguer
            ->useLogName('Utilisateur') // Utilise un nom personnalisé pour le log
            ->dontSubmitEmptyLogs(); // Empêche l'enregistrement des logs vides
    }

    // Méthode pour manipuler l'activité en fonction du rôle
    public function tapActivity(\Spatie\Activitylog\Contracts\Activity $activity, string $eventName)
    {
        if (auth()->check() && auth()->user()->hasRole('Developpeur')) {
            // Exclut les Developpeurs des logs d'activité
            $activity->causer_id = null;
            $activity->causer_type = null;
            return null;
        }
    }
}
