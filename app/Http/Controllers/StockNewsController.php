<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class StockNewsController extends Controller
{
   private $equityMap = [];

    public function __construct()
    {
        $this->equityMap = $this->loadEquityCsv();
    }

    public function index()
    {
        $apiKey = env('NEWS_API_KEY');

        $response = Http::get('https://newsapi.org/v2/everything', [
            'q' => 'Indian stock market OR NSE OR BSE',
            'language' => 'en',
            'sortBy' => 'publishedAt',
            'pageSize' => 50,
            'apiKey' => $apiKey
        ]);

        $news = $response->json()['articles'] ?? [];

       $filtered = collect($news)->map(function ($article) {

    $title = strtoupper($article['title'] ?? '');
    $symbolData = $this->matchSymbolFromTitle($title);

    if ($symbolData) {
        $article['symbol'] = $symbolData['symbol'];
        $article['instrument_key'] = "NSE_EQ|" . $symbolData['isin'];
        return $article;
    }

    return null;

})->filter()->values();

$page = request()->get('page', 1);
$perPage = 10;

$paginated = new \Illuminate\Pagination\LengthAwarePaginator(
    $filtered->forPage($page, $perPage),
    $filtered->count(),
    $perPage,
    $page,
    ['path' => request()->url(), 'query' => request()->query()]
);

return view('stock-news', ['news' => $paginated]);
    }

    public function analyse(Request $request)
    {
        $title = strtoupper($request->title);
        $publishedAt = Carbon::parse($request->publishedAt)->timezone('Asia/Kolkata');

        $symbolData = $this->matchSymbolFromTitle($title);

        if (!$symbolData) {
            return response()->json([
                'volume_spike' => false,
                'price_change_percent' => 0,
                'future_outlook' => 'Stock not identified',
                'sentiment' => []
            ]);
        }

        $instrumentKey = "NSE_EQ|" . $symbolData['isin'];

        $candles = $this->getUpstoxData($instrumentKey);

        if (empty($candles)) {
            return response()->json([
                'volume_spike' => false,
                'price_change_percent' => 0,
                'future_outlook' => 'No market data found',
                'sentiment' => []
            ]);
        }

        $impact = $this->calculateImpact($candles);

        $impact['sentiment'] = $this->getSentiment($title);
        $impact['future_outlook'] = $this->futurePrediction(
            $impact['volume_spike'],
            $impact['price_change_percent']
        );

        return response()->json($impact);
    }

    /* ========================================================= */

    private function loadEquityCsv()
    {
        $path = app_path('EQUITY_L.csv');

        if (!file_exists($path)) {
            return [];
        }

        $rows = array_map('str_getcsv', file($path));
        $header = array_shift($rows);

        $data = [];

        foreach ($rows as $row) {
            $data[] = [
                'symbol' => strtoupper(trim($row[0])),
                'name'   => strtoupper(trim($row[1])),
                'isin'   => trim($row[6]),
            ];
        }

        return $data;
    }

    private function matchSymbolFromTitle($title)
    {
        foreach ($this->equityMap as $stock) {

            if (
                str_contains($title, $stock['symbol']) ||
                str_contains($title, $stock['name'])
            ) {
                return $stock;
            }
        }

        return null;
    }

    private function getUpstoxData($symbol)
    {
        $to   = now()->format('Y-m-d');
        $from = now()->subDays(7)->format('Y-m-d');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('UPSTOX_ACCESS_TOKEN'),
        ])->get("https://api.upstox.com/v2/historical-candle/$symbol/day/$to/$from");

        return $response->json()['data']['candles'] ?? [];
    }

    private function calculateImpact($candles)
    {
        if (count($candles) < 2) {
            return [
                'volume_spike' => false,
                'price_change_percent' => 0,
            ];
        }

        $latest = $candles[0];
        $previous = $candles[1];

        $latestClose = $latest[4];
        $previousClose = $previous[4];

        $latestVolume = $latest[5];
        $previousVolume = $previous[5];

        $priceChangePercent = (($latestClose - $previousClose) / $previousClose) * 100;

        return [
            'volume_spike' => $latestVolume > $previousVolume,
            'price_change_percent' => round($priceChangePercent, 2),
        ];
    }

    private function getSentiment($text)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('HUGGINGFACE_TOKEN'),
        ])->post(
            'https://api-inference.huggingface.co/models/cardiffnlp/twitter-roberta-base-sentiment',
            ['inputs' => $text]
        );

        return $response->json();
    }

    private function futurePrediction($volumeSpike, $priceChange)
    {
        if ($volumeSpike && $priceChange > 0) {
            return "Bullish momentum possible 📈";
        }

        if ($volumeSpike && $priceChange < 0) {
            return "Bearish pressure increasing 📉";
        }

        return "Neutral movement expected 🤝";
    }
}