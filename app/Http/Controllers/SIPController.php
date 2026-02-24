<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SIPController extends Controller
{
    private $rapidApiKey;
    private $rapidApiHost;

    public function __construct()
    {
        $this->rapidApiKey = env('RAPIDAPI_KEY');
        $this->rapidApiHost = env('RAPIDAPI_IPO_HOST');
    }

    /**
     * Fetch SIP / Mutual Fund schemes from RapidAPI
     */
    private function fetchSIPs()
    {
        try {
            $response = Http::withHeaders([
                'X-RapidAPI-Key' => $this->rapidApiKey,
                'X-RapidAPI-Host' => $this->rapidApiHost,
            ])->get("https://{$this->rapidApiHost}/mutual-funds");

            if (!$response->successful()) return [];

            $data = $response->json();
dd($data);
            // Map data to friendly format
            return array_map(function($item) {
                return [
                    'name' => $item['fundName'] ?? 'N/A',
                    'amc' => $item['amc'] ?? 'N/A',
                    'nav' => $item['nav'] ?? 'N/A',
                    'minSIPAmount' => $item['minSIP'] ?? 'N/A',
                    'frequency' => 'Monthly', // usually monthly
                    'url' => $item['url'] ?? '#',
                ];
            }, $data['funds'] ?? []);

        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Show SIP page
     */
    public function index()
    {
        $sips = $this->fetchSIPs();
        return view('sensex.sip', compact('sips'));
    }
}