<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDayAgent extends Model
{
    use HasFactory;
    protected $fillable = [
        'loanDay_id',
        'transaction_id',
        'cashier_id',
        'amount',
        'agent_id',
        'agentType',
        'status',
        'deleted_userId',
        'deleted_agentType',
        'deleted_at',
        'deleteObservation',
        'deletedKey'
    ];

    public function loanDay()
    {
        return $this->belongsTo(LoanDay::class, 'loanDay_id');
    }
    
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
