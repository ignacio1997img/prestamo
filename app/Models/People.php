<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class People extends Model
{
    use HasFactory;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'ci',
        'first_name',
        'last_name1',
        'last_name2',
        'birth_date',
        'email',
        'cell_phone',
        'phone',
        'street',
        'home',
        'zone',
        'streetB',
        'homeB',
        'zoneB',

        'gender',
        'image',
        'facebook',
        'instagram',
        'tiktok',
        'status',
        'token',
        'register_userId',
        'deleted_at',
        'deleted_userId'
    ]; 
    public function name()
    {
        return $this->last_name1.' '.$this->last_name2.' '.$this->first_name;
    }
}
