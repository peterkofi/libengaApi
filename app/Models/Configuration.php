<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;
    protected $fillable=[
        'Taux',
        'Commission1',
        'Commission2',
        'pass_retrait',
    ];
}
