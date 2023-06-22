<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashierMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashier_id', 'user_id', 'balance', 'amount', 'description', 'type'
    ];

    public function cashier(){
        return $this->belongsTo(Cashier::class, 'cashier_id');
    }

    // public function cashier_from(){
    //     return $this->belongsTo(Cashier::class, 'cashier_id_from');
    // }

    // public function cashier_to(){
    //     return $this->belongsTo(Cashier::class, 'cashier_id_to');
    // }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
