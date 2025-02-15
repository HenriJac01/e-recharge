<?php

namespace App\Http\Controllers\dgi;

use App\Models\Transmission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransmisController extends Controller
{
    public function transmis(){
        $transmits = Transmission::whereHas('operator', function($query) {
            $query->whereIn('name', ['Yas', 'Orange', 'Airtel']); // Liste des opérateurs
        })->latest()
        ->paginate(10);
        return view('dgi/transmission',compact('transmits'));
    }

}
