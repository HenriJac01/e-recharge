<?php

namespace App\Http\Controllers\dgi;

use Carbon\Carbon;
use App\Models\Operator;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeDgiController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('auth/login');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashDgi()
    {
        $operateurs = ['Yas', 'Airtel', 'Orange']; // Liste des opérateurs

        //balance
     // Récupérer le total du chiffre d'affaire par opérateur pour le mois et l'année actuels
     $balance = DB::table('transmissions')
     ->join('operators', 'transmissions.operator_id', '=', 'operators.id')
     ->whereIn('operators.name', $operateurs) // Filtrer pour plusieurs opérateurs
     ->whereYear('transmissions.created_at', Carbon::now()->year) // Filtrer par l'année actuelle
     ->whereMonth('transmissions.created_at', Carbon::now()->month) // Filtrer par le mois actuel
     ->select('operators.name as operator', DB::raw('IFNULL(SUM(transmissions.chiffre_daffaire), 0) as cab')) // S'assurer que la somme est 0 si vide
     ->groupBy('operators.name') // Grouper par opérateur
     ->get(); // Récupérer tous les résultats

    // Calculer la somme totale de tous les opérateurs
    $totalCab = $balance->sum('cab'); // Additionne les sommes de chaque opérateur

    //taxes
    $taxe = DB::table('transmissions')
    ->join('operators', 'transmissions.operator_id', '=', 'operators.id')
    ->whereIn('operators.name', $operateurs) // Filtrer pour plusieurs opérateurs
    ->whereYear('transmissions.created_at', Carbon::now()->year) // Filtrer par l'année actuelle
    ->whereMonth('transmissions.created_at', Carbon::now()->month) // Filtrer par le mois actuel
    ->select(DB::raw('IFNULL(SUM(transmissions.droit_daccise), 0) as da')) 
    ->groupBy('operators.name') // Grouper par opérateur
    ->get(); // Récupérer tous les résultats
        
    // Calculer la somme totale de la taxe de tous les opérateurs  
    $totatTaxe = $taxe->sum('da');

    $yas = ['Yas'];
    // Récupérer le total du chiffre d'affaire par Yas pour le mois et l'année actuels
    $caYas =  DB::table('transmissions')
    ->join('operators', 'transmissions.operator_id', '=', 'operators.id')
    ->whereIn('operators.name', $yas) 
    ->whereYear('transmissions.created_at', Carbon::now()->year) // Filtrer par l'année actuelle
    ->whereMonth('transmissions.created_at', Carbon::now()->month) // Filtrer par le mois actuel
    ->select('operators.name as operator', DB::raw('IFNULL(SUM(transmissions.chiffre_daffaire), 0) as caYas')) // S'assurer que la somme est 0 si vide
    ->groupBy('operators.name') // Grouper par opérateur
    ->get(); // Récupérer tous les résultats
    // Calculer la somme totale de Yas
    $totalCaYas = $caYas->sum('caYas'); // Additionne les sommes de chaque opérateur

    $Orange = ['Orange'];
    // Récupérer le total du chiffre d'affaire par Yas pour le mois et l'année actuels
    $caOrange =  DB::table('transmissions')
    ->join('operators', 'transmissions.operator_id', '=', 'operators.id')
    ->whereIn('operators.name', $Orange)
    ->whereYear('transmissions.created_at', Carbon::now()->year) // Filtrer par l'année actuelle
    ->whereMonth('transmissions.created_at', Carbon::now()->month) // Filtrer par le mois actuel
    ->select('operators.name as operator', DB::raw('IFNULL(SUM(transmissions.chiffre_daffaire), 0) as caOrange')) // S'assurer que la somme est 0 si vide
    ->groupBy('operators.name') // Grouper par opérateur
    ->get(); // Récupérer tous les résultats
    // Calculer la somme totale de l'Orange
    $totalcaOrange = $caOrange->sum('caOrange'); // Additionne les sommes de chaque opérateur

    $Airtel = ['Airtel'];
    // Récupérer le total du chiffre d'affaire par Yas pour le mois et l'année actuels
    $caAirtel =  DB::table('transmissions')
    ->join('operators', 'transmissions.operator_id', '=', 'operators.id')
    ->whereIn('operators.name', $Airtel) 
    ->whereYear('transmissions.created_at', Carbon::now()->year) // Filtrer par l'année actuelle
    ->whereMonth('transmissions.created_at', Carbon::now()->month) // Filtrer par le mois actuel
    ->select('operators.name as operator', DB::raw('IFNULL(SUM(transmissions.chiffre_daffaire), 0) as caAirtel')) // S'assurer que la somme est 0 si vide
    ->groupBy('operators.name') // Grouper par opérateur
    ->get(); // Récupérer tous les résultats
    // Calculer la somme totale de l'Airtel
    $totalcaAirtel = $caAirtel->sum('caAirtel'); // Additionne les sommes de chaque opérateur


      // Statistiques par mois pour la recette
      $monthlyRecette = DB::table('transmissions')
      ->join('operators', 'transmissions.operator_id', '=', 'operators.id') // Joindre les opérateurs
      ->whereIn('operators.name', $operateurs) // Filtrer pour plusieurs opérateurs
        ->selectRaw('
          DATE_FORMAT(transmissions.created_at, "%m-%Y") as monthRecette, 
          SUM(transmissions.droit_daccise) as total_recette
      ')
      ->groupBy(DB::raw('DATE_FORMAT(transmissions.created_at, "%m-%Y")')) // Grouper par mois
      ->orderBy(DB::raw('DATE_FORMAT(transmissions.created_at, "%m-%Y")')) // Trier par mois
      ->get();

      $repartitionTaxes = DB::table('transmissions')
      ->join('operators', 'transmissions.operator_id', '=', 'operators.id')
      ->whereIn('operators.name', $operateurs)
      ->select('operators.name as operatorTaxe', DB::raw('SUM(transmissions.droit_daccise) as taxation'))
      ->groupBy('operators.name')
      ->get();
  
        // Récupérer les données sous forme de tableau
        $chartData = $repartitionTaxes->map(function($taxe) {
            return [
                'operatorTaxe' => $taxe->operatorTaxe,
                'taxation' => $taxe->taxation,
            ];
        });

    
    // Statistiques par mois pour Yas
    $monthlyYas = DB::table('transmissions')
    ->join('operators', 'transmissions.operator_id', '=', 'operators.id') // Joindre les opérateurs
    ->whereIn('operators.name', $yas) // Filtrer uniquement Yas
    ->selectRaw('
        DATE_FORMAT(transmissions.created_at, "%m-%Y") as monthYas, 
        IFNULL(SUM(transmissions.chiffre_daffaire), 0) as chiAffYAs
    ')
    ->groupBy(DB::raw('DATE_FORMAT(transmissions.created_at, "%m-%Y")')) // Grouper par mois
    ->orderBy(DB::raw('DATE_FORMAT(transmissions.created_at, "%m-%Y")')) // Trier par mois
     ->get();


    // Statistiques par mois pour Orange
    $monthlyOrange = DB::table('transmissions')
    ->join('operators', 'transmissions.operator_id', '=', 'operators.id') 
    ->whereIn('operators.name', $Orange) // Filtrer uniquement Orange
     ->selectRaw('
        DATE_FORMAT(transmissions.created_at, "%m-%Y") as monthYas, 
        IFNULL(SUM(transmissions.chiffre_daffaire), 0) as chiAffOrange
    ') 
    ->groupBy(DB::raw('DATE_FORMAT(transmissions.created_at, "%m-%Y")')) // Grouper par mois
    ->orderBy(DB::raw('DATE_FORMAT(transmissions.created_at, "%m-%Y")')) // Trier par mois
    ->get();


    // Statistiques par mois pour Airtel
    $monthlyAirtel = DB::table('transmissions')
    ->join('operators', 'transmissions.operator_id', '=', 'operators.id') 
    ->whereIn('operators.name', $Airtel) // Filtrer uniquement Airtel
    ->selectRaw('
        DATE_FORMAT(transmissions.created_at, "%m-%Y") as monthYas, 
        IFNULL(SUM(transmissions.chiffre_daffaire), 0) as chiAffAirtel
    ')
    ->groupBy(DB::raw('DATE_FORMAT(transmissions.created_at, "%m-%Y")')) // Grouper par mois
    ->orderBy(DB::raw('DATE_FORMAT(transmissions.created_at, "%m-%Y")')) // Trier par mois
    ->get();


    // Statistiques pour le taxe par mois de Yas
    $taxeYas = DB::table('transmissions')
    ->join('operators', 'transmissions.operator_id', '=', 'operators.id') 
    ->whereIn('operators.name', $yas)
     ->selectRaw('
          DATE_FORMAT(transmissions.created_at, "%m-%Y") as mmonthTaxeYas, 
          IFNULL(SUM(transmissions.droit_daccise), 0) as taxeYas
    ') // Faire la somme du chiffre d'affaires
    ->groupBy(DB::raw('DATE_FORMAT(transmissions.created_at, "%m-%Y")')) // Grouper par mois
    ->orderBy(DB::raw('DATE_FORMAT(transmissions.created_at, "%m-%Y")')) // Trier par mois
    ->get();


     // Statistiques pour le taxe par mois de l'Orange
     $taxeOrange = DB::table('transmissions')
     ->join('operators', 'transmissions.operator_id', '=', 'operators.id') 
     ->whereIn('operators.name', $Orange) 
      ->selectRaw('
           DATE_FORMAT(transmissions.created_at, "%m-%Y") as mmonthTaxeYas, 
           IFNULL(SUM(transmissions.droit_daccise), 0) as taxeOrange
     ') // Faire la somme du chiffre d'affaires
     ->groupBy(DB::raw('DATE_FORMAT(transmissions.created_at, "%m-%Y")')) // Grouper par mois
     ->orderBy(DB::raw('DATE_FORMAT(transmissions.created_at, "%m-%Y")')) // Trier par mois
     ->get();


      // Statistiques pour le taxe par mois de l'Airtel
      $taxeAirtel = DB::table('transmissions')
      ->join('operators', 'transmissions.operator_id', '=', 'operators.id') 
      ->whereIn('operators.name', $Airtel) 
       ->selectRaw('
            DATE_FORMAT(transmissions.created_at, "%m-%Y") as mmonthTaxeYas, 
            IFNULL(SUM(transmissions.droit_daccise), 0) as taxeAirtel
      ') // Faire la somme du chiffre d'affaires
      ->groupBy(DB::raw('DATE_FORMAT(transmissions.created_at, "%m-%Y")')) // Grouper par mois
      ->orderBy(DB::raw('DATE_FORMAT(transmissions.created_at, "%m-%Y")')) // Trier par mois
      ->get();


     $taxes = DB::table('transmissions')
    ->join('operators', 'transmissions.operator_id', '=', 'operators.id') // Joindre les opérateurs
    ->whereIn('operators.name', $operateurs) // Filtrer les opérateurs
    ->whereYear('transmissions.created_at', '>=', Carbon::now()->subYears(4)->year) // Filtrer les 5 dernières années
    ->selectRaw('
        YEAR(transmissions.created_at) as annee, 
        SUM(transmissions.droit_daccise) as total_taxe
    ')
    ->groupBy(DB::raw('YEAR(transmissions.created_at)')) // Grouper par année
    ->orderBy(DB::raw('YEAR(transmissions.created_at)')) // Trier par année
    ->get();

    // Transformer les données pour JavaScript
    $annees = $taxes->pluck('annee')->map(fn($val) => (float) $val); // Liste des années
    $taxesParAnnee = $taxes->pluck('total_taxe'); // Liste des taxes

    return view('dgi/dashDgi',compact('totalCab','totatTaxe','totalCaYas','totalcaOrange','totalcaAirtel','monthlyRecette','chartData','monthlyYas','monthlyOrange','monthlyAirtel','taxeYas','taxeOrange','taxeAirtel','taxesParAnnee','annees'));
    }
   
}
