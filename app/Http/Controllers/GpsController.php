<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GpsController extends Controller
{
    public function index()
    {
        return view('gps.browse');
    }
}
