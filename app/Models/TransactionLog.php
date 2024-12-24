<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $fillable = [
        'transaction_id',
        'message',
        'status',
        'sent_at',
        'received_at',
        'created_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'sent_at',
        'received_at'
    ];

    protected $appends = ['status_color'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'created' => 'text-blue-500',
            'pending' => 'text-yellow-500',
            'completed' => 'text-green-500',
            'error' => 'text-red-500',
            default => 'text-gray-500'
        };
    }
}
