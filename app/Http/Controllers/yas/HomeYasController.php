<?php

namespace App\Http\Controllers\yas;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class HomeYasController extends Controller
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
    public function dashYas()
    {
    // Total des transactions de type "achat" ou "transfert_in" et l'opérateur
    $totalCount = DB::table('transactions')
    ->join('operators', 'transactions.operator_id', '=', 'operators.id')
    ->whereIn('transactions.type', ['achat', 'transfer_in'])
    ->where('operators.name', 'Yas')
    ->select(DB::raw('COUNT(transactions.id) as count'))
    ->groupBy('operators.name')
    ->first();

    $totalTransactions = $totalCount ? $totalCount->count : 0;
   
    // Transactions réussies de type "achat" ou "transfert_in" et l'opérateur
    $totalAmount = DB::table('transactions')
        ->join('operators', 'transactions.operator_id', '=', 'operators.id')
        ->whereIn('transactions.type', ['achat', 'transfer_in'])
        ->where('operators.name', 'Yas')
        ->select(DB::raw('SUM(amount) as amount'))
        ->groupBy('operators.name')
        ->first();

    $successfulTransactions = $totalAmount ? $totalAmount->amount : 0;
    
    // Calcul du taux de succès pour "achat" et "transfert_in"
    if ($totalTransactions > 0) {
        $rateTransaction = ($successfulTransactions / $totalTransactions) * 0.0000001;
    } else {
        $rateTransaction = 0; // Ou toute autre valeur par défaut
    }
    
    //calcul du taux de nombre de recharge
    $rateRecharge  = $totalTransactions * 0.0001;

     //calcul du taux de montant de recharge
     $rateTotalRecharge  = $successfulTransactions * 0.00000001 ;
        
        // Statistiques des transactions
        $stats = [
            'total' =>  $totalTransactions,
            'total_amount' =>   $successfulTransactions,
            'success_rate' => $rateTransaction,
            'nombre_recharge_rate' => $rateRecharge,
            'total_recharge_rate' => $rateTotalRecharge,
        ];
        /* REVENUS */
        // Statistiques par jours 
       $dailyRevenues = DB::table('transactions')
        ->join('operators', 'transactions.operator_id', '=', 'operators.id')
        ->whereIn('transactions.type', ['achat', 'transfer_in'])
        ->where('operators.name', 'Yas')
        ->selectRaw('DATE(transactions.created_at) as day, SUM(transactions.amount) as total_revenue')
        ->groupBy(DB::raw('DATE(transactions.created_at)'))
        ->orderBy('day')
        ->get();

        
         // Statistiques par semaines
        $weeklyRevenues = DB::table('transactions')
    ->join('operators', 'transactions.operator_id', '=', 'operators.id')
    ->whereIn('transactions.type', ['achat', 'transfer_in'])
    ->where('operators.name', 'Yas')
    ->selectRaw(' WEEK(transactions.created_at) as week, SUM(transactions.amount) as total_revenue')
    ->groupBy(DB::raw('WEEK(transactions.created_at)'))
    ->orderBy('week')
    ->get();


        // Statistiques par mois
        $monthlyRevenues = DB::table('transactions')
        ->join('operators', 'transactions.operator_id', '=', 'operators.id') // Joindre les opérateurs
        ->whereIn('transactions.type', ['achat', 'transfer_in']) // Filtrer par type de transaction
        ->where('operators.name', 'Yas') // Filtrer par le nom de l'opérateur ('Yas')
        ->selectRaw('
            DATE_FORMAT(transactions.created_at, "%Y-%m") as month, 
            SUM(transactions.amount) as total_revenue
        ')
        ->groupBy(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")')) // Grouper par mois
        ->orderBy(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")')) // Trier par mois
        ->get();



        // Statistiques par années
        $yearlyRevenues = DB::table('transactions')
        ->join('operators', 'transactions.operator_id', '=', 'operators.id')
        ->whereIn('transactions.type', ['achat', 'transfer_in'])
        ->where('operators.name', 'Yas')  // Filtrer par l'opérateur, ici "Yas"
        ->selectRaw('
            DATE_FORMAT(transactions.created_at, "%Y") as year, 
            SUM(transactions.amount) as total_revenue
        ')
        ->groupBy(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'))
        ->orderBy('year')
        ->get();

        /* REVENUS */
        // Statistiques par jours 
        $daily_stats = Transaction::selectRaw(
        'DATE(created_at) as day, 
        SUM(amount) as total_revenue'
        )->whereIn('type', ['achat', 'transfer_in'])
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('day')
        ->get();

         // Statistiques par semaines
        $weeklyRevenues = Transaction::selectRaw('WEEK(created_at) as week,
        SUM(amount) as total_revenue'
        )->whereIn('type', ['achat', 'transfer_in'])
        ->groupBy(DB::raw('WEEK(created_at)'))
        ->orderBy('week')
        ->get();


        // Statistiques par mois
        $monthlyRevenues = Transaction::selectRaw('
        DATE_FORMAT(created_at, "%Y-%m") AS month, 
        SUM(amount) AS total_revenue
        ')
        ->whereIn('type', ['achat', 'transfer_in'])
        ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
        ->orderBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
        ->get();

        // Statistiques par années
        $yearlyRevenues = Transaction::selectRaw('
        YEAR(created_at) AS year, 
        SUM(amount) AS total_revenue
        ')
        ->whereIn('type', ['achat', 'transfer_in'])
        ->groupBy(DB::raw('YEAR(created_at)'))
        ->orderBy(DB::raw('YEAR(created_at)'))
        ->get();

        /* EVOLUTION */
        // Statistiques par jours 
        $dailyEvolution = DB::table('transactions')
        ->join('operators', 'transactions.operator_id', '=', 'operators.id')
        ->whereIn('transactions.type', ['achat', 'transfer_in'])
        ->where('operators.name', 'Yas')
        ->selectRaw('DATE(transactions.created_at) as day,  COUNT(*) as count')
        ->groupBy(DB::raw('DATE(transactions.created_at)'))
        ->orderBy('day')
        ->get();

         // Statistiques par semaines
        $weeklyEvolution = Transaction::selectRaw('WEEK(created_at) as week,
        COUNT(*) as count'
        )->whereIn('type', ['achat', 'transfer_in'])
        ->groupBy(DB::raw('WEEK(created_at)'))
        ->orderBy('week')
        ->get();

        // Statistiques par mois
        $monthlyEvolution = Transaction::selectRaw('
        DATE_FORMAT(created_at, "%Y-%m") AS month, 
        COUNT(*) AS count
        ')
        ->whereIn('type', ['achat', 'transfer_in']) // Filtrer par type
        ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")')) // Groupement par mois
        ->orderBy('month') // Tri par mois
        ->get();

        // Statistiques par années
        $yearlyEvolution = Transaction::selectRaw('
        YEAR(created_at) AS year, 
        COUNT(*) as count
        ')
        ->whereIn('type', ['achat', 'transfer_in'])
        ->groupBy(DB::raw('YEAR(created_at)'))
        ->orderBy('year')
        ->get();
         /* EVOLUTION */            

        // Transactions récentes
        $recent_transactions = Transaction::with([
            'sender:id,name,phone_number',
            'transfer.receiver:id,name,phone_number'
        ])
        ->join('operators', 'transactions.operator_id', '=', 'operators.id') // Jointure avec la table des opérateurs
        ->where('operators.name', 'Yas') // Filtre pour l'opérateur avec le nom 'Yas'
        ->latest() // Récupère les transactions les plus récentes
        ->take(3) // Limite à 3 transactions
        ->select('transactions.*') // Sélectionne toutes les colonnes de la table transactions
        ->get()
        ->map(function ($transaction) {
            return [
                'reference' => $transaction->reference,
                'type' => $transaction->type,
                'amount' => $transaction->amount,
                'status' => $transaction->status,
                'created_at' => $transaction->created_at,
                'sender' => [
                    'name' => optional($transaction->sender)->name,
                    'phone' => optional($transaction->sender)->phone_number
                ],
                'recipient' => [
                    'name' => optional(optional($transaction->transfer)->receiver)->name,
                    'phone' => optional(optional($transaction->transfer)->receiver)->phone_number
                ]
            ];
        });

        return view('Yas/dashYas', compact('stats', 'dailyRevenues','weeklyRevenues','monthlyRevenues','yearlyRevenues','recent_transactions','dailyEvolution','weeklyEvolution','monthlyEvolution','yearlyEvolution'));

    }
}
