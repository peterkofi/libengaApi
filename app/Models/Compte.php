<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Operation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;

    protected $fillable=[
        'NumeroCompte',
        'idClient',
        'mise',
        'typeCompte',
        'devise',
        'total',
        'dette',
        'MontantRetire',
        'CommissionTouche',       
        'CycleR',    
        'CycleD',    
        'NbrCycle', 
        'Cloture',
    ];




    /**
     * Get the client that owns the Compte
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
    * Get all of the operations for the Compte
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */

    public function operations()
    {
        return $this->hasMany(Operation::class, 'compte_id', 'id');
    }
    
 
}
