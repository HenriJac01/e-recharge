<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Operator;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les opérateurs
        $orange = Operator::where('name', 'Orange')->first();
        $yas = Operator::where('name', 'Yas')->first();
        $airtel = Operator::where('name', 'Airtel')->first();

        if (!$orange || !$yas || !$airtel) {
            throw new \Exception('Les opérateurs doivent être créés avant les clients');
        }

        // Créer quelques clients de test
        $clients = [
            [
                'name' => 'Koto',
                'cin' => '201051454836',
                'adress' => 'Beravina',
                'phone_number' => '0323232709',
                'balance' => 1000.00,
                'secret_code' => '1234', // Code secret simple
                'operator_id' => $orange->id
            ],
            [
                'name' => 'Jules',
                'cin' => '201056148377',
                'adress' => 'Antala',
                'phone_number' => '0349908829',
                'balance' => 500.00,
                'secret_code' => '4321', // Code secret simple
                'operator_id' => $yas->id
            ],
            [   'name' => 'Rado',
                'cin' => '201051014836',
                'adress' => 'Betroka',
                'phone_number' => '0333232709',
                'balance' => 750.00,
                'secret_code' => '5678', // Code secret simple
                'operator_id' => $airtel->id
            ]
        ];

        foreach ($clients as $clientData) {
            Client::create($clientData);
        }
    }
}
