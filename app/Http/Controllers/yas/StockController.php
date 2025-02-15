<?php

namespace App\Http\Controllers\yas;

use App\Models\Stock;
use App\Models\Operator;
use Illuminate\Http\Request;
use Flasher\Laravel\Facade\Flasher;
use Flasher\Prime\FlasherInterface;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    public function stock()
    {
        $stocks = Stock::with(['operator:id,name'])
        ->wherehas('operator',function($query){
            $query->where('name','Yas');
        })->latest()->paginate(10);
        return view('yas/tables.stock',compact('stocks'));
    }

    public function edit($id){
        $stock = Stock::findOrFail($id);
        $operators = Operator::all();
        return view('yas/formes.editStock',compact('stock','operators'));

    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        try {
            // Valider les données entrantes
            $request->validate([
                'quantity' => 'required|numeric|min:0',
                'minimum_threshold' => 'required|numeric|min:0',
                'operator_id' => 'required|exists:operators,id',
            ]);

            // Trouver le stock
            $stock = Stock::findOrFail($id);

            // Mettre à jour les données du stock
            $stock->update([
                'quantity' => $request->quantity,
                'minimum_threshold' => $request->minimum_threshold,
                'operator_id' => $request->operator_id,
            ]);

            // Message de succès
            Flasher::addSuccess('Stock modifié avec succès !');

            // Redirection pour les requêtes classiques
            return redirect()->route('yas/tables.stock');
        } catch (\Exception $e) {
            // Ajouter un message d'erreur avec Flasher
            $flasher->addError('Erreur : ' . $e->getMessage());

            // Réponse pour les requêtes AJAX
            if ($request->ajax()) 
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur : ' . $e->getMessage(),
                ], 500);
            }

            // Retour pour les requêtes classiques
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $stock = Stock::find($id);

        if($stock)
        {
            $stock->delete();
            Flasher::addSuccess('Stock supprimé avec succès !');
        }
        else
        {
            return redirect()->back();
        }
    }
}
