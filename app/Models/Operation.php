<?php

namespace App\Models;

use App\Models\Compte;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'compte_id',
        'montant',
        'Type_operation',
        'montant_touche_client', 
        'date'
    ];

    /**
     * Get the Compte that owns the Operation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }


    /**
     * Get the user that owns the Operation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


   
}

