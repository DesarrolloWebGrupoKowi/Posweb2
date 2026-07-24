<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrdenesOracleController extends Controller
{
    public function index(Request $request)
    {

        return view('OrdenesOracle/index');
    }
}
