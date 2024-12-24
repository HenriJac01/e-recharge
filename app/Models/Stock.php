<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Operator;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'quantity',
        'minimum_threshold'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'minimum_threshold' => 'decimal:2'
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
