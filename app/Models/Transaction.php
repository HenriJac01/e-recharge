<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Client;
use App\Models\Operator;
use App\Models\TransactionLog;
use App\Models\TransferHistory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'type',
        'amount',
        'status',
        'client_id',
        'operator_id',
        'transfer_id',
        'channel'
    ];

    protected $with = ['transfer', 'transfer.receiver', 'transfer.receiver.operator'];

    /**
     * L'expéditeur de la transaction (utilise client_id)
     */
    public function sender()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Le transfert associé à la transaction
     */
    public function transfer()
    {
        return $this->belongsTo(TransferHistory::class, 'transfer_id');
    }

    /**
     * L'opérateur associé à la transaction
     */
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    /**
     * Les logs de la transaction
     */
    public function logs()
    {
        return $this->hasMany(TransactionLog::class);
    }

    /**
     * Scope pour les transactions complétées
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope pour les transactions en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour les transactions en erreur
     */
    public function scopeError($query)
    {
        return $query->where('status', 'error');
    }

    /**
     * Scope pour les transactions d'aujourd'hui
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope pour les transactions de cette semaine
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope pour les transactions de ce mois
     */
    public function scopeThisMonth($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }
}
