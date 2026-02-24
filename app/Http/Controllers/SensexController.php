<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SensexController extends Controller
{
       private function fetchIndex($symbol)
    {
        try {
            $response = Http::timeout(10)->get(
                "https://query1.finance.yahoo.com/v8/finance/chart/{$symbol}",
                [
                    'interval' => '5m',
                    'range'    => '1d'
                ]
            );

            if (!$response->successful()) return null;

            $json = $response->json();

            if (!isset($json['chart']['result'][0])) return null;

            $result = $json['chart']['result'][0];

            $closes = array_filter($result['indicators']['quote'][0]['close'] ?? []);

            if (count($closes) < 2) return null;

            $first = reset($closes);
            $last  = end($closes);

            return [
                'price' => round($last, 2),
                'change' => round($last - $first, 2),
                'percent' => $first != 0 ? round((($last - $first) / $first) * 100, 2) : 0,
                'timestamps' => $result['timestamp'] ?? [],
                'closes' => array_values($closes)
            ];

        } catch (\Exception $e) {
            return null;
        }
    }

    private function fetchStockList($type)
    {
        try {
            $response = Http::get(
                "https://query1.finance.yahoo.com/v1/finance/screener/predefined/saved",
                [
                    'scrIds' => $type,
                    'count'  => 10
                ]
            );

            if (!$response->successful()) return [];

            return $response['finance']['result'][0]['quotes'] ?? [];

        } catch (\Exception $e) {
            return [];
        }
    }
private function getMarketNews()
{
    $response = Http::timeout(10)->get('https://newsapi.org/v2/everything', [
        'q' => '(Sensex OR "Nifty 50" OR NSE OR BSE OR "stock market" OR "share market" OR "market rally" OR "market crash")',
        'language' => 'en',
        'sortBy' => 'publishedAt', // latest first
        'pageSize' => 15,
        'from' => now()->subDays(2)->toDateString(), // last 48 hrs
        'domains' => 'economictimes.indiatimes.com,moneycontrol.com,business-standard.com,livemint.com,cnbctv18.com',
        'apiKey' => env('NEWS_API_KEY'),
    ]);

    if ($response->successful()) {

        $articles = $response->json()['articles'] ?? [];

        // Remove duplicates + empty titles
        return collect($articles)
            ->filter(fn($a) => !empty($a['title']))
            ->unique('title')
            ->values()
            ->toArray();
    }

    return [];
}
    public function index()
    {
        $nifty  = $this->fetchIndex('%5ENSEI');
        $sensex = $this->fetchIndex('%5EBSESN');

        $gainers = $this->fetchStockList('day_gainers');
        $losers  = $this->fetchStockList('day_losers');
        $mostActive = $this->fetchStockList('most_actives');
         $marketNews = $this->getMarketNews();

        return view('sensex.index', compact(
            'nifty',
            'sensex',
            'gainers',
            'losers',
            'mostActive',
            'marketNews'
        ));
    }

}
