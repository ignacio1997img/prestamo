<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteCollector extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'user_id',
        'observation',
        'status',
        'register_userId',
        'deleted_at',
        'deleted_userId',
        'deleted_agentType'
    ];

    public function collector()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
