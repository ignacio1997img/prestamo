<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vault extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
        'closed_at',
        'deleted_at'
    ];

    public function details(){
        return $this->hasMany(VaultDetail::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}

