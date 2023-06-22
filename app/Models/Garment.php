<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garment extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'people_id',
        'article_id',
        'categoryGarment_id',
        'brandGarment_id',
        'modelGarment_id',
        'article',
        'categoryGarment',
        'brandGarment',
        'modelGarment',
        'articleDescription',
        'cashier_id',
        'type',
        'date',
        'amountLoan',
        'amountLoanDollar',
        'priceDollar',
        'amountPorcentage',
        'porcentage',
        'amountTotal',
        'cantMonth',
        'observation',
        'status',

        'delivered',
        'dateDelivered',
        'delivered_userId',
        'delivered_agentType',

        'success_userId',
        'success_agentType',

        'cashierRegister_id',
        'register_userId',
        'register_agentType',
        
        'deleted_userId',
        'deleted_agentType',
        'deleteObservation',
        'deletedKey',

        'deleted_at'
    ];



}
