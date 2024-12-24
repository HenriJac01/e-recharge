<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $table = 'transfer_history';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'operator_id',
        'amount',
        'status',
        'reference'
    ];

    public function sender()
    {
        return $this->belongsTo(Client::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Client::class, 'receiver_id');
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'transfer_id');
    }

    public static function generateReference()
    {
        return 'TRF' . date('YmdHis') . rand(1000, 9999);
    }
}
