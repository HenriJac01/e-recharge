<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class USSDRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ussd_code' => 'required|string|starts_with:*|ends_with:#',
            'input' => 'nullable|string|max:20',
            'session_id' => 'nullable|string|max:50'
        ];
    }

    public function messages()
    {
        return [
            'ussd_code.required' => 'Le code USSD est requis',
            'ussd_code.starts_with' => 'Le code USSD doit commencer par *',
            'ussd_code.ends_with' => 'Le code USSD doit se terminer par #',
            'input.max' => 'L\'entrée ne doit pas dépasser 20 caractères',
            'session_id.max' => 'L\'ID de session n\'est pas valide'
        ];
    }
}
