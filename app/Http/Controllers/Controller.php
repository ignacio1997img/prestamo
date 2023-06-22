<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Cashier;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    

    public function agent($id)
    {
        return DB::table('users as u')
            ->join('roles as r', 'r.id', 'u.role_id')
            ->where('u.id', $id)
            ->select('u.id', 'u.name', 'r.name as role')
            ->first();
    }


    // Funcion para ver la caja abierta
    public function cashierOpen()
    {
        return Cashier::with(['movements' => function($q){
            $q->where('deleted_at', NULL);
        }])
        ->where('user_id', Auth::user()->id)
        ->where('status', 'abierta')
        ->where('deleted_at', NULL)->first();
    }




}
