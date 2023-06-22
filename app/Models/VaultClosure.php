<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaultClosure extends Model
{
    use HasFactory;

    protected $fillable = [
        'vault_id', 'user_id', 'observations'
    ];

    public function details(){
        return $this->hasMany(VaultClosureDetail::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
