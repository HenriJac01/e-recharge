<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette demande.
     *
     * @return bool
     */
    public function authorize()
    {
        // Retourne true pour autoriser la requête
        return true;
    }

    /**
     * Obtenez les règles de validation qui s'appliquent à la demande.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'adress' => 'required|string|max:255',
            'cin' => 'required|string|size:12', // Supprimé unique ici
            'phone_number' => 'required|string|size:10', // Supprimé unique ici
            'secret_code' => 'required|string|size:4|unique:clients,secret_code',
            'balance' => 'required|numeric|min:0',
            'operator_id' => 'required|exists:operators,id',
        ];
    }

    /**
     * Obtenez les messages de validation personnalisés.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'cin.required' => 'Le CIN est obligatoire.',
            'phone_number.required' => 'Le numéro de téléphone est obligatoire.',
            'secret_code.required' => 'Le code secret est obligatoire.',
            'secret_code.unique' => 'Le code secret est déjà enregistré.',
            'operator_id.exists' => 'L\'opérateur sélectionné n\'existe pas.',
        ];
    }
}
