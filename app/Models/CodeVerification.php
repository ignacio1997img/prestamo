<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeVerification extends Model
{
    use HasFactory;
    protected $fillable = [
        'loan_id',
        'cell_phone',
        'code',
        'type',
        'status'
    ];



}
