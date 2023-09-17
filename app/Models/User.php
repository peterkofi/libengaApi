<?php

namespace App\Models;

use App\Models\Operation;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'last_name',
        'profile',
        'address',
        'type',
        'state',
        'email',
        'password',
        'Autorisation'
    ];

    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    /**
    * Get all of the operations for the User
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function operations()
    {
        return $this->hasMany(Operation::class, 'user_id', 'id');
    }

}
