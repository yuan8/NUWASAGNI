<?php

namespace App\Http\Controllers\DASH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DASH extends Controller
{
    //

    public function index(){
    	return view('dash.index');
    }
}
