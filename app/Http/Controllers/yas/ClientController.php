<?php

namespace App\Http\Controllers\yas;

use App\Models\Client;
use App\Models\Operator;
use Illuminate\Http\Request;
use Flasher\Laravel\Facade\Flasher;
use App\Http\Controllers\Controller;
use Flasher\Prime\FlasherInterface; 

class ClientController extends Controller
{
    public function client()
    {
        $clients = Client::with(['operator:id,name']) 
        ->whereHas('operator', function($query) {
            $query->where('name', 'Yas'); 
        })->latest()->paginate(10);
        return view('yas/tables.client',compact('clients'));
    }

    public function selectOperator()
    {
        $operators =  Operator::all();

        return view('yas/formes.client',compact('operators'));

    }

    public function store(Request $request)
{
    // Validation des données
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'adress' => 'required|string|max:255',
        'cin' => 'required|string|size:12',
        'phone_number' => 'required|string|size:10',
        'secret_code' => 'required|string|size:4|unique:clients,secret_code',
        'balance' => 'required|numeric|min:0',
        'operator_id' => 'required|exists:operators,id',
    ]);

    // Vérification pour le `cin`
    if (Client::where('cin', $validatedData['cin'])->exists()) 
    {
        Flasher::addError('Le CIN est déjà enregistré.');
        return back()->withInput();
    }

    // Vérification pour le `phone_number`
    if (Client::where('phone_number', $validatedData['phone_number'])->exists()) 
    {
        Flasher::addError('Ce numéro de téléphone est déjà utilisé.');
        return back()->withInput();
    }

    // Enregistrement du client
    $client = new Client();
    $client->name = $validatedData['name'];
    $client->adress = $validatedData['adress'];
    $client->cin = $validatedData['cin'];
    $client->phone_number = $validatedData['phone_number'];
    $client->secret_code = $validatedData['secret_code'];
    $client->balance = $validatedData['balance'];
    $client->operator_id = $validatedData['operator_id'];
    $client->save();

    // Message de succès
    Flasher::addSuccess('Client ajouté avec succès !');

    return redirect()->route('yas/tables.client'); 
}

public function edit($id){
    $client = Client::findOrFail($id);
    $operators = Operator::all();
    return view('yas/formes.editClient',compact('client','operators'));
}

public function update(Request $request, $id, FlasherInterface $flasher)
    {
        try {
            // Valider les données entrantes
            $request->validate([
                'name' => 'required|string|max:255',
                'adress' => 'required|string|max:255',
                'cin' => 'required|string|size:12',
                'phone_number' => 'required|string|size:10',
                'secret_code' => 'required|string|size:4',
                'balance' => 'required|numeric|min:0',
                'operator_id' => 'required|exists:operators,id',
            ]);

            // Trouver le client
            $client = Client::findOrFail($id);

            // Mettre à jour les données du client
            $client->update([
                'name' => $request->name,
                'adress' => $request->adress,
                'cin' => $request->cin,
                'phone_number' => $request->phone_number,
                'secret_code' => $request->secret_code,
                'balance' => $request->balance,
                'operator_id' => $request->operator_id,
            ]);

            // Réponse pour les requêtes AJAX
            if ($request->ajax()) 
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Client modifié avec succès !',
                    'client' => $client,
                    
                ]);
            }

            // Redirection pour les requêtes classiques
            return redirect()->route('yas.tables.client');
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
        $client = Client::find($id);
       
        if ($client)
        {
            $client->delete();
            Flasher::addSuccess('Client supprimé avec succès !');
        }
        else 
        {
            return redirect()->back();
        }
    
}
}
