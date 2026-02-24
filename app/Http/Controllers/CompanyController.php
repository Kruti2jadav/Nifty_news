<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CompanyController extends Controller
{
    public function index()
    {
        return view('company');
    }

    public function search(Request $request)
    {
        $symbol = strtoupper($request->symbol);

        $accessToken = env('UPSTOX_ACCESS_TOKEN');

        // For NSE equity format
        $instrumentKey = "NSE_EQ|$symbol";

        $response = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'Accept' => 'application/json'
        ])->get("https://api.upstox.com/v2/market-quote/quotes", [
            'instrument_key' => $instrumentKey
        ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Unable to fetch data'
            ], 500);
        }

        $data = $response->json();

        if (!isset($data['data'][$instrumentKey])) {
            return response()->json([
                'error' => 'Invalid symbol'
            ], 404);
        }

        $quote = $data['data'][$instrumentKey];

        return response()->json([
            'name' => $symbol,
            'price' => $quote['last_price'],
            'change' => $quote['net_change'],
            'percent' => $quote['net_change_percent'],
            'market_cap' => $quote['market_cap'] ?? null,
            'volume' => $quote['volume'] ?? null,
        ]);
    }
}