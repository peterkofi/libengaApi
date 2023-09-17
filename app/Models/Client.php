<?php

namespace App\Models;

use App\Models\Compte;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable=[
        'NumeroClient',
        'nom',
        'prenom',
        'Delegue1',
        'Delegue2',
        'photoProfil',
        'photoSignature',
        'NumeroTelephone',
        'adresse',       
        'DateNaissance',      
          
    ];


   /**
    * Get all of the comptes for the Client
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function comptes()
   {
       return $this->hasMany(Compte::class, 'client_id', 'id');
   }


   
}
