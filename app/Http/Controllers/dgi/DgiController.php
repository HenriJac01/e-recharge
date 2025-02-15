<?php

namespace App\Http\Controllers\dgi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DgiController extends Controller
{
    public function dgi(){
        return view('dgi/tables.dgi');
    }

    public function formDgi(){
        return view('dgi/formes.dgi');
    }

    public function store(){
        return redirect()->route('dgi/tables.dgi'); 
    }
}
