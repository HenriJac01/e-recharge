<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Operator;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\TransferHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class USSDService
{
    const SESSION_DURATION = 100; // 5 minutes
    const MAX_AUTH_ATTEMPTS = 3;

    protected function getMenu()
    {
        return "Menu Principal:\n1. Acheter du crédit\n2. Vérifier solde\n3. Transfert de crédit\n0. Quitter";
    }

    public function handleUSSDRequest($ussdCode, $input = null, $sessionId = null, $phoneNumber = null)
    {
        try {
            // Log les paramètres reçus pour le débogage
            Log::info('USSD Request Started:', [
                'ussd_code' => $ussdCode,
                'input' => $input,
                'session_id' => $sessionId,
                'phone_number' => $phoneNumber
            ]);

            // Extraire le numéro de téléphone si non fourni
            if (!$phoneNumber) {
                $phoneNumber = $this->extractPhoneNumber($ussdCode);
                if (!$phoneNumber) {
                    return [
                        'message' => "Code USSD invalide. Format attendu: *XXX*numero#",
                        'end' => true
                    ];
                }
            }

            // Vérifier si le client existe
            $client = Client::where('phone_number', $phoneNumber)->first();
            if (!$client) {
                return [
                    'message' => "Numéro non enregistré.",
                    'end' => true
                ];
            }

            // Si pas de session_id, en générer un
            if (!$sessionId) {
                $sessionId = md5($phoneNumber . time());
            }

            // Traiter la requête
            $response = $this->handleRequest($phoneNumber, $input, $sessionId);

            // Log de fin de requête
            Log::info('USSD Request Completed:', [
                'phone_number' => $phoneNumber,
                'response' => $response
            ]);

            return [
                'message' => $response,
                'session_id' => $sessionId,
                'end' => false
            ];

        } catch (\Exception $e) {
            Log::error('USSD Request Failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ussd_code' => $ussdCode,
                'phone_number' => $phoneNumber
            ]);

            return [
                'message' => "Une erreur est survenue. Veuillez réessayer.",
                'end' => true
            ];
        }
    }

    public function handleRequest($phoneNumber, $input = null, $sessionId = null)
    {
        $sessionKey = "ussd_session_{$sessionId}";
        
        // Log de début de traitement
        Log::info('Processing USSD Request:', [
            'phone_number' => $phoneNumber,
            'input' => $input,
            'session_id' => $sessionId,
            'session_key' => $sessionKey
        ]);

        // Si c'est un nouveau USSD (pas d'input), on commence une nouvelle session
        if ($input === null || $input === '') {
            $newSession = [
                'step' => 'menu',
                'phone_number' => $phoneNumber
            ];
            
            Cache::put($sessionKey, $newSession, self::SESSION_DURATION);
            
            Log::info('New Session Created:', [
                'session' => $newSession,
                'session_key' => $sessionKey
            ]);
            
            return $this->getMenu();
        }

        // Récupérer la session existante
        $session = Cache::get($sessionKey);
        
        // Log de la session récupérée
        Log::info('Retrieved Session:', [
            'session' => $session,
            'session_key' => $sessionKey
        ]);

        // Vérifier si la session existe
        if (!$session) {
            Log::warning('Session Not Found:', [
                'session_key' => $sessionKey
            ]);
            
            $newSession = [
                'step' => 'menu',
                'phone_number' => $phoneNumber
            ];
            
            Cache::put($sessionKey, $newSession, self::SESSION_DURATION);
            return $this->getMenu();
        }

        try {
            Log::info('Processing Step:', [
                'step' => $session['step'],
                'input' => $input
            ]);

            $result = match ($session['step']) {
                'menu' => $this->handleMenuChoice($phoneNumber, $input, $sessionId),
                'purchase_amount' => $this->handlePurchaseAmount($phoneNumber, $input, $sessionId),
                'purchase_auth' => $this->handlePurchaseAuth($phoneNumber, $input, $sessionId),
                'transfer_recipient' => $this->handleTransferRecipient($phoneNumber, $input, $sessionId),
                'transfer_amount' => $this->handleTransferAmount($phoneNumber, $input, $sessionId),
                'transfer_auth' => $this->handleTransferAuth($phoneNumber, $input, $sessionId),
                'check_balance_auth' => $this->handleCheckBalanceAuth($phoneNumber, $input, $sessionId),
                default => $this->getMenu()
            };

            Log::info('Step Completed:', [
                'step' => $session['step'],
                'result' => $result
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('Step Processing Error:', [
                'step' => $session['step'] ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Cache::put($sessionKey, [
                'step' => 'menu',
                'phone_number' => $phoneNumber
            ], self::SESSION_DURATION);

            return "Une erreur est survenue. Veuillez réessayer.\n" . $this->getMenu();
        }
    }

    protected function isSessionActive($sessionId)
    {
        $sessionKey = "ussd_session_{$sessionId}";
        return Cache::has($sessionKey);
    }

    protected function extractPhoneNumber($ussdCode)
    {
        // Nettoyer le code USSD
        $ussdCode = trim($ussdCode);
        
        // Supprimer les espaces éventuels
        $ussdCode = str_replace(' ', '', $ussdCode);
        
        // Format attendu: *XXX*phone_number# ou *XXX*phone_number*input#
        if (preg_match('/^\*(\d+)\*(\d+)(?:\*[^#]*)?#?$/', $ussdCode, $matches)) {
            return $matches[2];
        }
        
        // Si le format ne correspond pas, essayer de trouver juste les chiffres
        if (preg_match('/\*(\d+)\*(\d{8,})/', $ussdCode, $matches)) {
            return $matches[2];
        }
        
        return null;
    }

    protected function handleMenuChoice($phoneNumber, $choice, $sessionId)
    {
        $sessionKey = "ussd_session_{$sessionId}";
        
        // Log pour le débogage
        Log::info('Menu Choice:', [
            'phone_number' => $phoneNumber,
            'choice' => $choice,
            'session_id' => $sessionId,
            'session_key' => $sessionKey
        ]);

        try {
            // Vérifier si le client existe
            $client = Client::where('phone_number', $phoneNumber)->first();
            if (!$client) {
                Log::error('Client not found:', ['phone_number' => $phoneNumber]);
                throw new \Exception("Client non trouvé");
            }

            // Vérifier le choix du menu
            switch ($choice) {
                case '1': // Achat de crédit
                    Log::info('Starting purchase flow', [
                        'session_key' => $sessionKey,
                        'phone_number' => $phoneNumber
                    ]);

                    Cache::put($sessionKey, [
                        'step' => 'purchase_amount',
                        'phone_number' => $phoneNumber,
                        'operator_id' => $client->operator_id
                    ], self::SESSION_DURATION);
                    
                    return "Entrez le montant à acheter:";

                case '2': // Vérification de solde
                    Cache::put($sessionKey, [
                        'step' => 'check_balance_auth',
                        'phone_number' => $phoneNumber,
                        'operator_id' => $client->operator_id
                    ], self::SESSION_DURATION);
                    return "Entrez votre code secret:";

                case '3': // Transfert de crédit
                    Cache::put($sessionKey, [
                        'step' => 'transfer_recipient',
                        'phone_number' => $phoneNumber,
                        'operator_id' => $client->operator_id
                    ], self::SESSION_DURATION);
                    return "Entrez le numéro du destinataire:";

                case '0': // Quitter
                    Cache::forget($sessionKey);
                    return "Merci d'avoir utilisé notre service.";

                default:
                    return "Choix invalide.\n" . $this->getMenu();
            }
        } catch (\Exception $e) {
            Log::error('Menu Choice Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'phone_number' => $phoneNumber,
                'choice' => $choice,
                'session_key' => $sessionKey
            ]);
            
            Cache::forget($sessionKey);
            return "Une erreur est survenue.\n" . $this->getMenu();
        }
    }

    protected function handlePurchaseAmount($phoneNumber, $amount, $sessionId)
    {
        $sessionKey = "ussd_session_{$sessionId}";
        
        // Log pour le débogage
        Log::info('Purchase Amount:', [
            'phone_number' => $phoneNumber,
            'amount' => $amount,
            'session_id' => $sessionId,
            'session_key' => $sessionKey
        ]);

        try {
            // Vérifier le montant
            if (!is_numeric($amount) || $amount <= 0) {
                return "Montant invalide. Veuillez entrer un montant valide:";
            }

            // Récupérer la session
            $session = Cache::get($sessionKey);
            if (!$session || !isset($session['operator_id'])) {
                throw new \Exception("Session invalide");
            }

            // Vérifier si le client existe
            $client = Client::where('phone_number', $phoneNumber)->first();
            if (!$client) {
                throw new \Exception("Client non trouvé");
            }

            // Vérifier le stock disponible
            $stock = Stock::where('operator_id', $session['operator_id'])->first();
            if (!$stock) {
                throw new \Exception("Stock non trouvé");
            }

            // Vérifier si le stock est suffisant
            if ($stock->quantity - $stock->minimum_threshold < $amount) {
                throw new \Exception("Stock insuffisant");
            }

            // Mettre à jour la session avec le montant
            Cache::put($sessionKey, [
                'step' => 'purchase_auth',
                'phone_number' => $phoneNumber,
                'operator_id' => $session['operator_id'],
                'amount' => $amount,
                'attempts' => 0
            ], self::SESSION_DURATION);

        return "Entrez votre code secret pour confirmer l'achat de {$amount} Ar:";

        } catch (\Exception $e) {
            Log::error('Purchase Amount Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'phone_number' => $phoneNumber,
                'amount' => $amount
            ]);

            Cache::forget($sessionKey);
            return "Une erreur est survenue. Veuillez réessayer.\n" . $this->getMenu();
        }
    }

    protected function handleTransferRecipient($phoneNumber, $recipient, $sessionId)
    {
        $sessionKey = "ussd_session_{$sessionId}";
        
        // Valider le format du numéro
        if (!preg_match('/^\d{8,}$/', $recipient)) {
            return "Numéro invalide. Veuillez entrer un numéro valide:";
        }

        // Vérifier si le destinataire existe
        $receiverClient = Client::where('phone_number', $recipient)->first();
        if (!$receiverClient) {
            return "Destinataire non trouvé. Veuillez vérifier le numéro:";
        }

        // Vérifier que ce n'est pas le même numéro
        if ($recipient === $phoneNumber) {
            return "Vous ne pouvez pas transférer à vous-même. Entrez un autre numéro:";
        }

        Cache::put($sessionKey, [
            'step' => 'transfer_amount',
            'recipient' => $recipient
        ], self::SESSION_DURATION);

        return "Entrez le montant à transférer:";
    }

    protected function handleTransferAmount($phoneNumber, $amount, $sessionId)
    {
        $sessionKey = "ussd_session_{$sessionId}";
        $session = Cache::get($sessionKey);

        if (!is_numeric($amount) || $amount <= 0) {
            return "Montant invalide. Veuillez entrer un montant valide:";
        }

        $client = Client::where('phone_number', $phoneNumber)->first();
        
        // Vérifier le solde
        if ($client->balance < $amount) {
            return "Solde insuffisant. Votre solde: {$client->balance}";
        }

        Cache::put($sessionKey, [
            'step' => 'transfer_auth',
            'recipient' => $session['recipient'],
            'amount' => $amount,
            'attempts' => 0
        ], self::SESSION_DURATION);

        return "Entrez votre code secret pour confirmer le transfert de {$amount}:";
    }

    protected function handleTransferAuth($phoneNumber, $secretCode, $sessionId)
    {
        $sessionKey = "ussd_session_{$sessionId}";
        
        try {
            // Récupérer la session
            $session = Cache::get($sessionKey);
            
            // Log pour le débogage
            Log::info('Transfer Auth Attempt:', [
                'phone_number' => $phoneNumber,
                'session' => $session,
                'session_id' => $sessionId
            ]);

            if (!$session || !isset($session['amount']) || !isset($session['recipient'])) {
                Log::error('Invalid session data:', ['session' => $session]);
                throw new \Exception("Session invalide");
            }

            // Vérifier l'expéditeur
            $sender = Client::where('phone_number', $phoneNumber)->first();
            if (!$sender) {
                Log::error('Sender not found:', ['phone_number' => $phoneNumber]);
                throw new \Exception("Expéditeur non trouvé");
            }

            // Vérifier le destinataire
            $recipient = Client::where('phone_number', $session['recipient'])->first();
            if (!$recipient) {
                Log::error('Recipient not found:', ['phone_number' => $session['recipient']]);
                throw new \Exception("Destinataire non trouvé");
            }

            // Vérifier que l'expéditeur et le destinataire sont du même opérateur
            if ($sender->operator_id !== $recipient->operator_id) {
                Log::error('Different operators:', [
                    'sender_operator' => $sender->operator_id,
                    'recipient_operator' => $recipient->operator_id
                ]);
                throw new \Exception("Le transfert n'est possible qu'entre numéros du même opérateur");
            }

            // Vérifier le solde de l'expéditeur
            if ($sender->balance < $session['amount']) {
                Log::error('Insufficient balance:', [
                    'balance' => $sender->balance,
                    'amount' => $session['amount']
                ]);
                throw new \Exception("Solde insuffisant");
            }

            // Vérifier le code secret
            if (!$sender->verifySecretCode($secretCode)) {
                $attempts = ($session['attempts'] ?? 0) + 1;
                
                if ($attempts >= self::MAX_AUTH_ATTEMPTS) {
                    Log::warning('Max auth attempts reached:', [
                        'phone_number' => $phoneNumber,
                        'attempts' => $attempts
                    ]);
                    Cache::forget($sessionKey);
                    return "Nombre maximum de tentatives atteint. Veuillez réessayer plus tard.";
                }

                Cache::put($sessionKey, array_merge($session, [
                    'attempts' => $attempts
                ]), self::SESSION_DURATION);

                return "Code incorrect. Réessayez ({$attempts}/3):";
            }

            DB::beginTransaction();
            try {
                // Créer l'historique de transfert
                $reference = 'TRF' . date('YmdHis') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                
                $transfer = new TransferHistory();
                $transfer->sender_id = $sender->id;
                $transfer->receiver_id = $recipient->id;
                $transfer->operator_id = $sender->operator_id;
                $transfer->amount = $session['amount'];
                $transfer->status = 'completed';
                $transfer->reference = $reference;
                $transfer->save();

                // Créer la transaction pour l'expéditeur (débit)
                $senderTransaction = new Transaction();
                $senderTransaction->client_id = $sender->id;
                $senderTransaction->operator_id = $sender->operator_id;
                $senderTransaction->amount = -$session['amount']; // Montant négatif pour le débit
                $senderTransaction->type = 'transfer_out';
                $senderTransaction->status = 'completed';
                $senderTransaction->reference = $reference . '_OUT';
                $senderTransaction->transfer_id = $transfer->id;
                $senderTransaction->save();

                // Créer la transaction pour le destinataire (crédit)
                $recipientTransaction = new Transaction();
                $recipientTransaction->client_id = $recipient->id;
                $recipientTransaction->operator_id = $recipient->operator_id;
                $recipientTransaction->amount = $session['amount'];
                $recipientTransaction->type = 'transfer_in';
                $recipientTransaction->status = 'completed';
                $recipientTransaction->reference = $reference . '_IN';
                $recipientTransaction->transfer_id = $transfer->id;
                $recipientTransaction->save();

                // Mettre à jour les soldes
                $sender->balance -= $session['amount'];
                $sender->save();

                $recipient->balance += $session['amount'];
                $recipient->save();

                DB::commit();
                Cache::forget($sessionKey);

                return "Transfert de {$session['amount']} Ar effectué avec succès vers {$session['recipient']}.\n" .
                       "Votre nouveau solde: {$sender->balance} Ar";

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Transfer Transaction Error:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Transfer Auth Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Cache::forget($sessionKey);

            // Messages d'erreur spécifiques
            if (str_contains($e->getMessage(), "Session invalide")) {
                return "Session expirée. Veuillez recommencer l'opération.\n" . $this->getMenu();
            } else if (str_contains($e->getMessage(), "Expéditeur non trouvé")) {
                return "Numéro non enregistré.\n" . $this->getMenu();
            } else if (str_contains($e->getMessage(), "Destinataire non trouvé")) {
                return "Destinataire non trouvé.\n" . $this->getMenu();
            } else if (str_contains($e->getMessage(), "même opérateur")) {
                return "Le transfert n'est possible qu'entre numéros du même opérateur.\n" . $this->getMenu();
            } else if (str_contains($e->getMessage(), "Solde insuffisant")) {
                return "Solde insuffisant pour effectuer ce transfert.\n" . $this->getMenu();
            }

            return "Une erreur est survenue lors du transfert. Veuillez réessayer.\n" . $this->getMenu();
        }
    }

    protected function handlePurchaseAuth($phoneNumber, $secretCode, $sessionId)
    {
        $sessionKey = "ussd_session_{$sessionId}";
        
        try {
            // Récupérer la session
            $session = Cache::get($sessionKey);
            
            // Log pour le débogage
            Log::info('Purchase Auth Attempt:', [
                'phone_number' => $phoneNumber,
                'session' => $session,
                'session_id' => $sessionId,
                'session_key' => $sessionKey
            ]);

            // Vérifier la session
            if (!$session || !isset($session['amount']) || !isset($session['operator_id'])) {
                Log::error('Invalid session data:', [
                    'session' => $session,
                    'session_id' => $sessionId
                ]);
                throw new \Exception("Session invalide");
            }

            // Vérifier le client
            $client = Client::where('phone_number', $phoneNumber)->first();
            if (!$client) {
                Log::error('Client not found:', ['phone_number' => $phoneNumber]);
                throw new \Exception("Client non trouvé");
            }

            // Log avant vérification du code secret
            Log::info('Before secret code verification:', [
                'client_id' => $client->id,
                'phone_number' => $phoneNumber,
                'has_secret_code' => !empty($client->secret_code)
            ]);

            // Vérifier le code secret
            if (!$client->verifySecretCode($secretCode)) {
                $attempts = ($session['attempts'] ?? 0) + 1;
                
                if ($attempts >= self::MAX_AUTH_ATTEMPTS) {
                    Log::warning('Max auth attempts reached:', [
                        'phone_number' => $phoneNumber,
                        'attempts' => $attempts
                    ]);
                    Cache::forget($sessionKey);
                    return "Nombre maximum de tentatives atteint. Veuillez réessayer plus tard.";
                }

                Cache::put($sessionKey, [
                    'step' => 'purchase_auth',
                    'phone_number' => $phoneNumber,
                    'operator_id' => $session['operator_id'],
                    'amount' => $session['amount'],
                    'attempts' => $attempts
                ], self::SESSION_DURATION);

                return "Code incorrect. Réessayez ({$attempts}/3):";
            }

            DB::beginTransaction();
            try {
                // Vérifier le stock
                $stock = Stock::where('operator_id', $session['operator_id'])
                    ->lockForUpdate()
                    ->first();
                
                if (!$stock) {
                    Log::error('Stock not found:', [
                        'operator_id' => $session['operator_id']
                    ]);
                    throw new \Exception("Stock non trouvé");
                }

                // Log de vérification du stock
                Log::info('Stock check:', [
                    'current_quantity' => $stock->quantity,
                    'minimum_threshold' => $stock->minimum_threshold,
                    'requested_amount' => $session['amount'],
                    'available_stock' => $stock->quantity - $stock->minimum_threshold
                ]);

                // Vérifier si le stock est suffisant
                if ($stock->quantity - $stock->minimum_threshold < $session['amount']) {
                    Log::error('Insufficient stock:', [
                        'current_quantity' => $stock->quantity,
                        'minimum_threshold' => $stock->minimum_threshold,
                        'requested_amount' => $session['amount']
                    ]);
                    throw new \Exception("Stock insuffisant");
                }

                // Créer la transaction avec une référence unique
                $reference = 'PUR' . date('YmdHis') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                
                // Log avant création de transaction
                Log::info('Creating transaction:', [
                    'client_id' => $client->id,
                    'operator_id' => $session['operator_id'],
                    'amount' => $session['amount'],
                    'reference' => $reference
                ]);

                $transaction = new Transaction();
                $transaction->client_id = $client->id;
                $transaction->operator_id = $session['operator_id'];
                $transaction->amount = $session['amount'];
                $transaction->type = 'achat';
                $transaction->status = 'completed';
                $transaction->reference = $reference;
                $transaction->transfer_id = null; // Pour les achats, pas de transfer_id
                $transaction->save();

                // Mettre à jour le stock
                $stock->quantity -= $session['amount'];
                $stock->save();

                // Mettre à jour le solde du client
                $client->balance += $session['amount'];
                $client->save();

                // Log du succès de la transaction
                Log::info('Purchase successful:', [
                    'transaction_id' => $transaction->id,
                    'reference' => $reference,
                    'new_balance' => $client->balance,
                    'new_stock' => $stock->quantity
                ]);

                DB::commit();
                Cache::forget($sessionKey);

                return "Achat de {$session['amount']} Ar effectué avec succès.\nVotre nouveau solde: {$client->balance} Ar";

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Purchase Transaction Error:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'client_id' => $client->id,
                    'amount' => $session['amount'],
                    'operator_id' => $session['operator_id']
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Purchase Auth Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'phone_number' => $phoneNumber,
                'session_id' => $sessionId
            ]);

            Cache::forget($sessionKey);
            
            // Messages d'erreur plus spécifiques
            if (str_contains($e->getMessage(), "Stock non trouvé")) {
                return "Service temporairement indisponible. Veuillez réessayer plus tard.\n" . $this->getMenu();
            } else if (str_contains($e->getMessage(), "Stock insuffisant")) {
                return "Stock insuffisant. Veuillez réessayer plus tard.\n" . $this->getMenu();
            } else if (str_contains($e->getMessage(), "Session invalide")) {
                return "Session expirée. Veuillez recommencer l'opération.\n" . $this->getMenu();
            } else if (str_contains($e->getMessage(), "Client non trouvé")) {
                return "Numéro non enregistré.\n" . $this->getMenu();
            }
            
            return "Une erreur est survenue lors de l'achat. Veuillez réessayer.\n" . $this->getMenu();
        }
    }

    protected function handleCheckBalanceAuth($phoneNumber, $secretCode, $sessionId)
    {
        $sessionKey = "ussd_session_{$sessionId}";
        $session = Cache::get($sessionKey);

        $client = Client::where('phone_number', $phoneNumber)->first();
        
        if (!$client || !$client->verifySecretCode($secretCode)) {
            $attempts = ($session['attempts'] ?? 0) + 1;
            
            if ($attempts >= self::MAX_AUTH_ATTEMPTS) {
                Cache::forget($sessionKey);
                return "Nombre maximum de tentatives atteint. Veuillez réessayer plus tard.";
            }

            Cache::put($sessionKey, [
                'step' => 'check_balance_auth',
                'attempts' => $attempts
            ], self::SESSION_DURATION);

            return "Code incorrect. Réessayez ({$attempts}/3):";
        }

        Cache::forget($sessionKey);
        return "Votre solde est de: {$client->balance} Ar";
    }
}
