<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransferHistory extends Model
{
    use HasFactory;

    protected $table = 'transfer_history';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'operator_id',
        'amount',
        'status',
        'reference'
    ];

    protected $with = ['receiver', 'receiver.operator'];

    /**
     * L'expéditeur du transfert
     */
    public function sender()
    {
        return $this->belongsTo(Client::class, 'sender_id');
    }

    /**
     * Le destinataire du transfert
     */
    public function receiver()
    {
        return $this->belongsTo(Client::class, 'receiver_id');
    }

    /**
     * L'opérateur associé au transfert
     */
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    /**
     * La transaction associée au transfert
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'transfer_id');
    }
}
