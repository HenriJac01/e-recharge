<?php

namespace App\Models;

use App\Models\Operator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'dgi_id',
        'nif',
        'chiffre_daffaire',
        'taux',
        'droit_daccise',
        'status'
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
