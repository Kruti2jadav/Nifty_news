<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CurrencyController extends Controller
{
    private $baseUrl;
    private $accessToken;

    public function __construct()
    {
        $this->baseUrl = env('UPSTOX_BASE_URL', 'https://api.upstox.com/v2');
        $this->accessToken = env('UPSTOX_ACCESS_TOKEN');
    }

    /**
     * Fetch live currency/forex quotes
     */
    private function fetchCurrencies()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/market/quotes/currency');

            if (!$response->successful()) return [];

            $data = $response->json();

            // Map to friendly structure
            return array_map(function ($item) {
                return [
                    'symbol' => $item['symbol'] ?? '',
                    'bid' => $item['bid'] ?? 'N/A',
                    'ask' => $item['ask'] ?? 'N/A',
                    'lastPrice' => $item['lastPrice'] ?? 'N/A',
                    'change' => $item['change'] ?? '0',
                    'percentChange' => $item['percentChange'] ?? '0',
                ];
            }, $data['data'] ?? []);

        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Show Currency page
     */
    public function index()
    {
        $currencies = $this->fetchCurrencies();
        return view('sensex.currency', compact('currencies'));
    }
}