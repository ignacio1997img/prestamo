<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRoute extends Model
{
    use HasFactory;
    protected $fillable = [
        'loan_id',
        'route_id',
        'observation',
        'status',
        'register_userId',
        'register_agentType',
        'deleted_at',
        'deleted_userId',
        'deleted_agentType',
        'deleteObservation'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    // public function agent()
    // {
    //     return $this->belongsTo(User::class, 'agent_id');
    // }

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

}
