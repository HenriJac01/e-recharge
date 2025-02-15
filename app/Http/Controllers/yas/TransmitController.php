<?php

namespace App\Http\Controllers\yas;

use Carbon\Carbon;
use App\Models\Dgi;
use App\Models\Operator;
use App\Models\Transaction;
use App\Models\Transmission;
use Illuminate\Http\Request;
use Flasher\Laravel\Facade\Flasher;
use Flasher\Prime\FlasherInterface;
use App\Http\Controllers\Controller;

class TransmitController extends Controller
{

    public function transmit(){
        $transmits = Transmission::with(['operator:id,name']) 
        ->whereHas('operator', function($query) {
            $query->where('name', 'Yas'); 
        })->latest()->paginate(10);
        return view('yas/tables.transmission',compact('transmits'));
    }

    public function create(){
        //Récupérer les opérateurs et DGI adresse
        $operators = Operator::all();
        $dgis = Dgi::all();

        // Récupérer la date actuelle
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Calculer la somme des recharges pour le mois en cours
         $chiffreDaffaire = Transaction::whereYear('created_at', $currentYear)
        ->whereMonth('created_at', $currentMonth)
        ->whereIn('type', ['achat', 'transfer_in'])
        ->sum('amount');

        return view('yas/formes.transmission',compact('operators','dgis','chiffreDaffaire'));
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'operator_id' => 'required|exists:operators,id',
        'dgi_id' => 'required|exists:dgis,id',
        'nif' => 'required|string|size:10'
    ]);

    try {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Vérifier si une transmission existe déjà pour le mois et l'année en cours
        $existingTransmission = Transmission::where('operator_id', $request->operator_id)
            ->where('dgi_id', $request->dgi_id)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->first();

        if ($existingTransmission) {
            //Flasher::addError('Une transmission pour cet opérateur et ce mois existe déjà.');

            return redirect()->route('yas/tables.transmission'); 
        }

        $chiffreDaffaire = Transaction::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->whereIn('type', ['achat', 'transfer_in'])
            ->sum('amount');

        $taxRate = $request->taux;
        $droit_daccise = $chiffreDaffaire * $taxRate;

        Transmission::create([
            'operator_id' => $request->operator_id,
            'dgi_id' => $request->dgi_id,
            'nif' => $request->nif,
            'chiffre_daffaire' => $chiffreDaffaire,
            'taux' => $taxRate,
            'droit_daccise' => $droit_daccise,
        ]);

        Flasher::addSuccess('Transmission enregistrée avec succès.');

        return redirect()->route('yas/tables.transmission'); 
    } catch (\Exception $e) {
        Flasher::addError('Erreur lors de l\'enregistrement : ' . $e->getMessage());

        return redirect()->back();
    }
}

public function edit($id){
    $transmit = Transmission::findOrFail($id);
    $operators = Operator::all();
    $dgis = Dgi::all();
    return view('yas/formes.editTransmit',compact('transmit','operators','dgis'));

}

public function update(Request $request, $id, FlasherInterface $flasher)
{
    try {
        // Validation des données
        $request->validate([
            'operator_id' => 'required|exists:operators,id',
            'dgi_id' => 'required|exists:dgis,id',
            'nif' => 'required|string|size:10',
        ]);

        // Récupérer la transmission à modifier
        $transmission = Transmission::findOrFail($id);

        // Vérification des doublons pour le même opérateur et mois, excluant la transmission actuelle
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $existingTransmission = Transmission::where('operator_id', $request->operator_id)
            ->where('dgi_id', $request->dgi_id)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('id', '!=', $id)
            ->first();

        if ($existingTransmission) {
            // Ajouter un message d'erreur et retourner pour AJAX ou requêtes classiques
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une transmission pour cet opérateur et ce mois existe déjà.',
                ], 400);
            }
            $flasher->addError('Une transmission pour cet opérateur et ce mois existe déjà.');
            return redirect()->route('yas.tables.transmission');
        }

        // Recalcul des valeurs
        $chiffreDaffaire = Transaction::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->whereIn('type', ['achat', 'transfer_in'])
            ->sum('amount');

        $droit_daccise = $chiffreDaffaire * $request->taux;

        // Mise à jour des données
        $transmission->update([
            'operator_id' => $request->operator_id,
            'dgi_id' => $request->dgi_id,
            'nif' => $request->nif,
            'chiffre_daffaire' => $chiffreDaffaire,
            'taux' => $request->taux,
            'droit_daccise' => $droit_daccise,
        ]);

        // Réponse pour les requêtes AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Transmission mise à jour avec succès !',
                'transmission' => $transmission,
            ]);
        }

        // Redirection pour les requêtes classiques
        $flasher->addSuccess('Transmission mise à jour avec succès.');
        return redirect()->route('yas/tables.transmission');
    } catch (\Exception $e) {
        // Gestion des erreurs avec Flasher et retour pour les deux types de requêtes
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage(),
            ], 500);
        }
        $flasher->addError('Erreur : ' . $e->getMessage());
        return back()->with('error', 'Erreur : ' . $e->getMessage());
    }
}


public function destroy($id)
{
    $transmit = Transmission::find($id);

    if($transmit)
    {
        $transmit->delete();
        Flasher::addSuccess('Transmission supprimé avec succès !');
    }
    else
    {
        return redirect()->back();
    }
}


}
