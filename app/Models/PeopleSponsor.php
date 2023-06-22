<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeopleSponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'people_id',
        'sponsor_id',
        'observation',
        'status',

        'register_userId',
        'register_agentType',
        'deleted_at',
        'deleted_userId',
        'deleted_agentType'
    ];
    public function people()
    {
        return $this->belongsTo(People::class, 'people_id');
    }
}
