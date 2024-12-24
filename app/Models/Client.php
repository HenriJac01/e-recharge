<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Transaction;
use App\Models\Operator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'name', 
        'adress',
        'cin', 
        'phone_number',
        'secret_code', 
        'balance'
    ];
    

    protected $hidden = [
        'secret_code'
    ];

    protected $casts = [
        'balance' => 'decimal:2'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    /**
     * Déduire un montant du solde du client de manière atomique
     */
    public function deductBalance($amount)
    {
        return \DB::transaction(function () use ($amount) {
            // Recharger l'instance avec verrouillage
            $this->refresh();
            $this->lockForUpdate();

            if ($this->balance < $amount) {
                throw new \Exception('Solde insuffisant');
            }

            // Mise à jour directe dans la base de données
            $affected = self::where('id', $this->id)
                ->where('balance', '>=', $amount)
                ->update([
                    'balance' => \DB::raw("balance - $amount")
                ]);

            if (!$affected) {
                throw new \Exception('Échec de la mise à jour du solde');
            }

            // Recharger l'instance avec le nouveau solde
            $this->refresh();
            
            return $this->balance;
        });
    }

    /**
     * Ajouter un montant au solde du client de manière atomique
     */
    public function addBalance($amount)
    {
        return \DB::transaction(function () use ($amount) {
            // Mise à jour directe dans la base de données
            $affected = self::where('id', $this->id)
                ->update([
                    'balance' => \DB::raw("balance + $amount")
                ]);

            if (!$affected) {
                throw new \Exception('Échec de la mise à jour du solde');
            }

            // Recharger l'instance avec le nouveau solde
            $this->refresh();
            
            return $this->balance;
        });
    }

    /**
     * Vérifier si le client a un solde suffisant
     */
    public function hasEnoughBalance($amount)
    {
        // Recharger l'instance pour avoir le solde le plus récent
        $this->refresh();
        return $this->balance >= $amount;
    }

    /**
     * Obtenir le solde actuel
     */
    public function getCurrentBalance()
    {
        // Recharger l'instance pour avoir le solde le plus récent
        $this->refresh();
        return $this->balance;
    }

    /**
     * Vérifier le code secret du client
     */
    public function verifySecretCode($code)
    {
        // Comparaison directe du code secret
        return $code === $this->secret_code;
    }
}
