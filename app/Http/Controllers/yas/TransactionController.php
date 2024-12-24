<?php

namespace App\Http\Controllers\yas;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Operator;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class TransactionController extends Controller
{
    public function transaction(Request $request)
    {
         /* Transactions de type "achat" et leur montant avec l'opérateur*/
         // Count des transactions de type "achat" 
         $totalCount = DB::table('transactions')
         ->join('operators', 'transactions.operator_id', '=', 'operators.id')
         ->where('transactions.type','achat')
         ->where('operators.name', 'Yas')
         ->select(DB::raw('COUNT(transactions.id) as count'))
         ->groupBy('operators.name')
         ->first();
    
         $achat = $totalCount ? $totalCount->count : 0;
    
        // Transactions amount de type "achat" 
        $amountAchat = DB::table('transactions')
        ->join('operators', 'transactions.operator_id', '=', 'operators.id')
        ->where('transactions.type', 'achat')
        ->where('operators.name', 'Yas')
        ->select(DB::raw('SUM(amount) as amount'))
        ->groupBy('operators.name')
        ->first();
    
        $achatTransactions = $amountAchat ? $amountAchat->amount : 0;
    
        /* Transactionsde type "achat" et leur montant avec l'opérateur*/
    
        /* Transactionsde type "transfert" et leur montant avec l'opérateur*/
         // Count des transactions de type "transfert" 
         $transfertCount = DB::table('transactions')
         ->join('operators', 'transactions.operator_id', '=', 'operators.id')
         ->where('transactions.type','transfer_in')
         ->where('operators.name', 'Yas')
         ->select(DB::raw('COUNT(transactions.id) as count'))
         ->groupBy('operators.name')
         ->first();
         $transert= $transfertCount ? $transfertCount->count : 0;
    
        // Transactions amount de type "achat" 
        $amountTransfert = DB::table('transactions')
        ->join('operators', 'transactions.operator_id', '=', 'operators.id')
        ->where('transactions.type', 'transfer_in')
        ->where('operators.name', 'Yas')
        ->select(DB::raw('SUM(amount) as amount'))
        ->groupBy('operators.name')
        ->first();
    
        $transfertTransactions = $amountTransfert ? $amountTransfert->amount : 0;
    
        /* Transactionsde type "transfert" et leur montant avec l'opérateur*/
            
            // Statistiques des transactions
            $stats = [
                'total_achat' => $achat,
                'total_amount_achat' => $achatTransactions,
                'total_transfert' => $transert,
                'total_amount_transfert' => $transfertTransactions,
            ];
    

        $query = Transaction::with([
            'sender:id,name,phone_number,balance,operator_id', 
            'sender.operator:id,name',
            'transfer',
            'transfer.receiver:id,name,phone_number,balance,operator_id',
            'transfer.receiver.operator:id,name',
            'operator:id,name',
            'logs' => function($query) {
                $query->orderBy('created_at', 'asc');
            }
        ])->whereHas('operator', function($query) {
            $query->where('name', 'Yas'); // Condition pour filtrer par le nom de l'opérateur
        });

        // Filtres
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
            }
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('amount_range')) {
            switch ($request->amount_range) {
                case 'small':
                    $query->where('amount', '<', 1000);
                    break;
                case 'medium':
                    $query->whereBetween('amount', [1000, 5000]);
                    break;
                case 'large':
                    $query->where('amount', '>', 5000);
                    break;
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('sender', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('transfer.receiver', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();

        // Données pour les filtres
        $types = Transaction::select('type')->distinct()->pluck('type');
        $statuses = Transaction::select('status')->distinct()->pluck('status');
        $operators = Operator::select('id', 'name')->get();

        return view('Yas/transaction', compact('transactions', 'types', 'statuses', 'operators','stats'));
    }

    public function history(Request $request)
    {
        $query = Transaction::with([
            'sender:id,name,phone_number,balance,operator_id', 
            'sender.operator:id,name',
            'transfer',
            'transfer.receiver:id,name,phone_number,balance,operator_id',
            'transfer.receiver.operator:id,name',
            'operator:id,name'
        ])
        ->whereHas('operator', function($query) {
            $query->where('name', 'Yas'); // Condition pour filtrer par le nom de l'opérateur
        });

        // Filtres
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
            }
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('amount_range')) {
            switch ($request->amount_range) {
                case 'small':
                    $query->where('amount', '<', 1000);
                    break;
                case 'medium':
                    $query->whereBetween('amount', [1000, 5000]);
                    break;
                case 'large':
                    $query->where('amount', '>', 5000);
                    break;
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('sender', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('transfer.receiver', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();
        $types = Transaction::select('type')->distinct()->pluck('type');
        $statuses = Transaction::select('status')->distinct()->pluck('status');

        return view('Yas/transaction', compact('transactions', 'types', 'statuses'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load([
            'sender:id,name,phone_number,balance,operator_id', 
            'sender.operator:id,name',
            'transfer',
            'transfer.receiver:id,name,phone_number,balance,operator_id',
            'transfer.receiver.operator:id,name',
            'operator:id,name',
            'logs'
        ]);
        
        return view('transactions.show', compact('transaction'));
    }

    public function export(Request $request)
    {
        $query = Transaction::with([
            'sender:id,name,phone_number,balance,operator_id', 
            'sender.operator:id,name',
            'transfer',
            'transfer.receiver:id,name,phone_number,balance,operator_id',
            'transfer.receiver.operator:id,name',
            'operator:id,name'
        ]);

        // Filtres
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
            }
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('amount_range')) {
            switch ($request->amount_range) {
                case 'small':
                    $query->where('amount', '<', 1000);
                    break;
                case 'medium':
                    $query->whereBetween('amount', [1000, 5000]);
                    break;
                case 'large':
                    $query->where('amount', '>', 5000);
                    break;
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('sender', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('transfer.receiver', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->latest()->get();


        // Filtres
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
            }
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('amount_range')) {
            switch ($request->amount_range) {
                case 'small':
                    $query->where('amount', '<', 1000);
                    break;
                case 'medium':
                    $query->whereBetween('amount', [1000, 5000]);
                    break;
                case 'large':
                    $query->where('amount', '>', 5000);
                    break;
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('sender', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('transfer.receiver', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->latest()->get();  

    
   
        return view('Yas/transaction',compact('transactions'));
    }

  
}
