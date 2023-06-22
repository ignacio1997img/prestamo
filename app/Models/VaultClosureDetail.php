<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaultClosureDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'vault_closure_id', 'cash_value', 'quantity'
    ];
}
