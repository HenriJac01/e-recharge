<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Stock;
use App\Models\Transaction;

class Operator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ussd_code'
    ];

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
