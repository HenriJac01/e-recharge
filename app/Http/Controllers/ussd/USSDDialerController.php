<?php

namespace App\Http\Controllers\ussd;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\Request;
use App\Services\USSDService;

class USSDDialerController extends Controller
{
    public function index()
    {
        $operators = Operator::all();
        return view('ussd.dialer', compact('operators'));
    }

    public function processUSSD(Request $request)
    {
        try {
            $validated = $request->validate([
                'ussd_code' => 'required|string',
                'input' => 'nullable|string',
                'session_id' => 'nullable|string',
                'phone_number' => 'required|string|regex:/^[0-9]{10}$/'
            ]);

            $ussdService = app(USSDService::class);
            $response = $ussdService->handleUSSDRequest(
                $validated['ussd_code'],
                $validated['input'] ?? null,
                $validated['session_id'] ?? null,
                $validated['phone_number']
            );

            return response()->json([
                'status' => 'success',
                'data' => $response
            ]);
        } catch (\Exception $e) {
            \Log::error('USSD Processing Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur est survenue. Veuillez réessayer.'
            ], 500);
        }
    }
}
