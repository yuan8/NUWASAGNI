<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KELEMBAGAAN extends Controller
{
    //

    public function index(){
    	return view('front.kelembagaan.index');
    }
}
