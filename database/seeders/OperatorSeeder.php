<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Operator;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            // Créer les opérateurs avec leurs codes USSD
            $operators = [
                [
                    'name' => 'Orange',
                    'ussd_code' => ' #144#',
                    'stock' => 10000000,
                    'threshold' => 500000
                ],
                [
                    'name' => 'Yas',
                    'ussd_code' => '*111#',
                    'stock' => 10000000,
                    'threshold' => 500000
                ],
                [
                    'name' => 'Airtel',
                    'ussd_code' => '*114#',
                    'stock' => 10000000,
                    'threshold' => 500000
                ]
            ];

            foreach ($operators as $operatorData) {
                $operator = Operator::create([
                    'name' => $operatorData['name'],
                    'ussd_code' => $operatorData['ussd_code'],
                    'status' => 'active'
                ]);

                // Créer un stock initial pour chaque opérateur
                Stock::create([
                    'operator_id' => $operator->id,
                    'quantity' => $operatorData['stock'],
                    'minimum_threshold' => $operatorData['threshold']
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
