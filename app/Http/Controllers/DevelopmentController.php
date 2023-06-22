<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevelopmentController extends Controller
{
    public function development()
    {
        return view('error.development');
    }
}
