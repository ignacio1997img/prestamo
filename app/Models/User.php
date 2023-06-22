<?php

namespace App\Models;

use App\Http\Controllers\RouteController;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use TCG\Voyager\Models\Role;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yadahan\AuthenticationLog\AuthenticationLogable;
use Illuminate\Foundation\Auth\User as Authenticatable;




class User extends \TCG\Voyager\Models\User
{
    // use HasApiTokens, HasFactory, Notifiable;
    use HasFactory, Notifiable, AuthenticationLogable;
    // protected $dates = ['deleted_at'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status',
        'registerUser_id',
        'ci'
    ];



    // public function roles()
    // {
    //     return $this->hasMany(Role::class,'role_id');
    // }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function routeCollector()
    {
        return $this->hasMany(RouteCollector::class, 'user_id');
    }
}
