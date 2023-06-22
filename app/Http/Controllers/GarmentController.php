<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Garment;
use App\Models\Cashier;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GarmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {   

        $user = Auth::user();

        $cashier = Cashier::with(['movements' => function($q){
                $q->where('deleted_at', NULL);
            }])
            ->where('user_id', Auth::user()->id)
            ->where('status', 'abierta')
            ->where('deleted_at', NULL)->first();
        // return $cashier;

        $balance = 0;
        if($cashier)
        {
            $cashier_id = $cashier->id;
            $balance = $cashier->movements->where('type', 'ingreso')->where('deleted_at', NULL)->sum('balance');
        }
        else
        {
            $cashier_id = 0;
        }
        return view('garment.browse', compact('cashier','balance', 'cashier_id'));
    }

    public function create()
    {
        // return 1;
        $cashier = Cashier::with(['movements' => function($q){
            $q->where('deleted_at', NULL);
        }])
        ->where('user_id', Auth::user()->id)
        ->where('status', 'abierta')
        ->where('deleted_at', NULL)->first();

        return view('garment.add', compact('cashier'));
    }
}
