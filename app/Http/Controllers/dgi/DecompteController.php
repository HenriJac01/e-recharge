<?php

namespace App\Http\Controllers\dgi;

use App\Models\Transmission;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use NumberToWords\NumberToWords;
use App\Http\Controllers\Controller;

class DecompteController extends Controller
{
    public function decompteDA($id)
    {
        $decompte = Transmission::with('operator')->findOrFail($id);
    
        // Calculer le total à payer (chiffre_daffaire + droit_daccise)
        $total_a_payer = $decompte->chiffre_daffaire + $decompte->droit_daccise;

        // Séparer la partie entière et décimale du total
        $wholePart = floor($total_a_payer); // Partie entière (1500)
        $decimalPart = round($total_a_payer - $wholePart, 2) * 100; // Partie décimale (80)

        // Convertir la partie entière en mots
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('fr');
        $wholePartInWords = $numberTransformer->toWords($wholePart); // Convertit entière en mots

        // Convertir la partie décimale en mots (centimes)
        $decimalPartInWords = $numberTransformer->toWords($decimalPart); // Convertit décimal en mots

        // Convertir la partie décimale en mots (centimes), seulement si elle n'est pas zéro
        $decimalPartInWords = $decimalPart > 0 ? $numberTransformer->toWords($decimalPart) : '';


        // Charger les données dynamiques
        $data = [
            'nif'=>$decompte->nif,
            'chiffre_daffaire'=>$decompte->chiffre_daffaire,
            'taux'=>$decompte->taux,
            'droit_daccise'=>$decompte->droit_daccise,
            'total_a_payer'=> $total_a_payer,
            'operator'=>$decompte->operator->name,
            'created_at' => $decompte->created_at->format('d/m/Y'),
            'total_in_words' => ucfirst($wholePartInWords), // Partie entière en mots
            'total_in_words_decimal' => ucfirst($decimalPartInWords), // Partie décimal en mots
        ];

        // Récupérer les informations
        $operatorName =$decompte->operator->name;
        $createdAt = $decompte->created_at->format('d-m-Y');

        // Générer le PDF
        $pdf = Pdf::loadView('dgi.decompte', compact('data'));

        // Mettre à jour le statut à "imprimé"
        $decompte->status = true;
        $decompte->save();
    
    // Retourner le PDF avec le nom dynamique basé sur l'opérateur et la date
    return $pdf->stream("{$operatorName}_{$createdAt}.pdf");

    }
}
