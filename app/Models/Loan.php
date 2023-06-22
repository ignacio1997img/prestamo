<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'people_id',
        'guarantor_id',
        'cashier_id',
        
        'code',
        'typeLoan',
        'date',
        'day',
        'month',
        'observation',

        'porcentage',
        'amountLoan',
        'debt',
        'amountPorcentage',
        'amountTotal',
        'status',
        'delivered',
        'dateDelivered',
        'transaction_id',
        
        'inspector_userId',
        'inspector_agentType',

        'success_userId',
        'success_agentType',
        
        'register_userId',
        'register_agentType',

        'deleted_at',

        'deleted_userId',
        'deleted_agentType',
        'deleteObservation',
        'deletedKey',

        'delivered_userId',
        'delivered_agentType',
        'cashierRegister_id',

        // 'destroyDate',
        // 'destroyObservation',
        // 'destroy_userId',
        // 'destroy_agentType'

        'notificationDate',
        'notificationQuantity'

    ];



    public function people()
    {
        return $this->belongsTo(People::class, 'people_id');
    }
    public function guarantor()
    {
        return $this->belongsTo(People::class, 'guarantor_id');
    }

    public function loanDay()
    {
        return $this->hasMany(LoanDay::class);
    }

    public function loanRoute()
    {
        return $this->hasMany(LoanRoute::class);
    }

    public function loanRequirement()
    {
        return $this->hasMany(LoanRequirement::class);
    }
    
    //para ver que persona es la que entrega el prestamo al beneficiario
    public function agentDelivered()
    {
        return $this->belongsTo(User::class, 'delivered_userId');
    }


    public function destroyUser_ALL()
    {
        return $this->belongsTo(User::class, 'destroy_userId');
    }
}
