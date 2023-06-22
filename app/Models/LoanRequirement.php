<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'latitude',
        'longitude',
        'ci',
        'luz',
        'croquis',
        'business',
        
        'success_userId',
        'success_agentType',
        'status',

        'register_userId',
        'register_agentType',
        
        'deleted_at',
        'deleted_userId',
        'deleted_agentType'
    ];
}
