<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Index;
use App\Models\Stock;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use App\Models\Catagories;
use App\Models\Subcatagory;
use App\Models\Articles;
use App\Models\ArticleMedia;
use App\Models\Users;

class MarketController extends Controller
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

            if (!$response->successful()) {
                return null;
            }

            $json = $response->json();

            if (!isset($json['chart']['result'][0])) {
                return null;
            }

            $result = $json['chart']['result'][0];

            $closes = array_filter(
                $result['indicators']['quote'][0]['close'] ?? []
            );

            if (count($closes) < 2) {
                return null;
            }

            $first = reset($closes);
            $last  = end($closes);

            return [
                'price'         => round($last, 2),
                'changePts'     => round($last - $first, 2),
                'changePercent' => $first != 0
                    ? round((($last - $first) / $first) * 100, 2)
                    : 0,
                'timestamps'    => $result['timestamp'] ?? [],
                'closes'        => array_values($closes)
            ];

        } catch (\Exception $e) {
            return null;
        }
    }

    /* ===============================
       FETCH SCREENER DATA
    =============================== */
    private function fetchScreener($type)
    {
        try {

            $response = Http::timeout(10)->get(
                "https://query1.finance.yahoo.com/v1/finance/screener/predefined/saved",
                [
                    'scrIds' => $type,
                    'count'  => 5
                ]
            );

            if (!$response->successful()) {
                return [];
            }

            $json = $response->json();

            return $json['finance']['result'][0]['quotes'] ?? [];

        } catch (\Exception $e) {
            return [];
        }
    }
private function fetchStockList($type)
{
    try {

        $url = "https://query1.finance.yahoo.com/v1/finance/screener/predefined/saved";

        $response = Http::get($url, [
            'scrIds' => $type,
            'count'  => 10
        ]);

        if (!$response->successful()) return [];

        return $response['finance']['result'][0]['quotes'] ?? [];

    } catch (\Exception $e) {
        return [];
    }
}

    /* ===============================
       HOME PAGE
    =============================== */
    public function home()
    {
        // MAIN INDICES
        $nifty  = $this->fetchIndex('%5ENSEI');
        $sensex = $this->fetchIndex('%5EBSESN');

        if (!$nifty) {
            return view('welcome', ['error' => 'Market data unavailable']);
        }

        // Define variables BEFORE compact
        $niftyPrice   = $nifty['price'];
        $niftyChange  = $nifty['changePercent'];
        $sensexPrice  = $sensex['price'] ?? null;
        $sensexChange = $sensex['changePercent'] ?? 0;

        // Chart
        $labels = [];
        foreach ($nifty['timestamps'] as $time) {
            $labels[] = date('H:i', $time);
        }

        $prices = $nifty['closes'];

        // Multiple Indices
        $indices = [
            'BSE Sensex' => '%5EBSESN',
            'NIFTY 50'   => '%5ENSEI',
            'NIFTY BANK' => '%5ENSEBANK',
            'NIFTY IT'   => '%5ECNXIT',
        ];

        $marketData = [];

        foreach ($indices as $name => $symbol) {

            $data = $this->fetchIndex($symbol);

            if ($data) {
                $marketData[$name] = [
                    'price'         => $data['price'],
                    'changePts'     => $data['changePts'],
                    'changePercent' => $data['changePercent'],
                ];

                 Index::create([
                'symbol' => $symbol,
                'name' => $name,
                'price' => $data['price'],
                'change_pts' => $data['changePts'],
                'change_percent' => $data['changePercent'],
            ]);
            }
        }
 
        // Screener Data (Dynamic)
        $gainers    = $this->fetchScreener('day_gainers');
        $losers     = $this->fetchScreener('day_losers');
        $mostActive = $this->fetchScreener('most_actives');

$mostActiveValue  = $this->fetchStockList('most_actives');
$topGainers       = $this->fetchStockList('day_gainers');
$topLosers        = $this->fetchStockList('day_losers');
$weekHigh         = $this->fetchStockList('52_week_high');
$weekLow          = $this->fetchStockList('52_week_low');

// Define mapping of stock list types to your DB types
$screeners = [
    'day_gainers'    => 'gainer',
    'day_losers'     => 'loser',
    'most_actives'   => 'most_active',
    '52_week_high'   => '52_week_high',
    '52_week_low'    => '52_week_low',
];

// Corresponding fetch functions
$stockLists = [
    'day_gainers'    => $topGainers,
    'day_losers'     => $topLosers,
    'most_actives'   => $mostActiveValue,
    '52_week_high'   => $weekHigh,
    '52_week_low'    => $weekLow,
];

// Save each stock in DB
foreach ($screeners as $scr => $type) {
    foreach ($stockLists[$scr] as $stock) {
        Stock::create([
            'symbol' => $stock['symbol'] ?? '',
            'name' => $stock['shortName'] ?? $stock['longName'] ?? '',
            'type' => $type,
            'price' => $stock['regularMarketPrice'] ?? 0,
            'change_pts' => $stock['regularMarketChange'] ?? 0,
            'change_percent' => $stock['regularMarketChangePercent'] ?? 0,
        ]);
    }
}

        return view('welcome', compact(
            'labels',
            'prices',
            'niftyPrice',
            'niftyChange',
            'sensexPrice',
            'sensexChange',
            'marketData',
            'gainers',
            'losers',
            'mostActive',
            'mostActiveValue',
    'topGainers',
    'topLosers',
    'weekHigh',
    'weekLow'
        ));
    }
private function fetchNewsByCategory($category)
{
    $endpoint = config('services.newsapi.endpoint');
    $apiKey   = config('services.newsapi.key');

    $indiaResponse = Http::get($endpoint, [
        'q'        => $category . ' India OR NSE OR BSE',
        'pageSize' => 50,
        'sortBy'   => 'publishedAt',
        'language' => 'en',
        'apiKey'   => $apiKey,
    ]);

    $globalResponse = Http::get($endpoint, [
        'q'        => $category . 'Global OR stock market OR global OR US OR Europe',
        'pageSize' => 50,
        'sortBy'   => 'publishedAt',
        'language' => 'en',
        'apiKey'   => $apiKey,
    ]);

    $format = function ($articles) {
        return collect($articles)->map(function ($article) {
            return [
                'title'        => $article['title'] ?? '',
                'subtitle'     => $article['description'] ?? '',
                'url'          => $article['url'] ?? '#',
                'source'       => $article['source']['name'] ?? 'News',
                'published_at' => $article['publishedAt'] ?? null,
                'urlToImage'   => $article['urlToImage'] ?? null,
            ];
        });
    };

    return [
        'india'  => $indiaResponse->successful()
                        ? $format($indiaResponse->json()['articles'] ?? [])
                        : collect(),

        'global' => $globalResponse->successful()
                        ? $format($globalResponse->json()['articles'] ?? [])
                        : collect(),
    ];
}

private function fetchRelatedNews($category)
{
    $endpoint = config('services.newsapi.endpoint');
    $apiKey   = config('services.newsapi.key');

    $response = Http::get($endpoint, [
        'q'        => $category,
        'pageSize' => 7,             // fewer for sidebar
        'sortBy'   => 'relevancy',   // sort by relevancy
        'language' => 'en',
        'apiKey'   => $apiKey,
    ]);

    if (!$response->successful()) {
        return [];
    }

    $articles = $response->json()['articles'] ?? [];

    return array_map(function($article) {
        return [
            'title'        => $article['title'] ?? '',
            'url'          => $article['url'] ?? '#',
            'source'       => $article['source']['name'] ?? '',
            'published_at' => $article['publishedAt'] ?? '',
            'urlToImage'   => $article['urlToImage'] ?? null,
        ];
    }, $articles);
}

 public function category($category)
{
    $category = strtolower($category); // normalize category name
    $data = [];
 
    $extraDataMap = [
        'markets' => ['type'=>'indices', 'symbols'=>['BSE Sensex'=>'%5EBSESN','NIFTY 50'=>'%5ENSEI']],
        'commodities' => ['type'=>'commodities', 'symbols'=>['GOLD','SILVER','CRUDEOIL']],
    ];

    if (isset($extraDataMap[$category])) {
        $map = $extraDataMap[$category];

        switch($map['type']) {
            case 'indices':
                $marketData = [];
                foreach ($map['symbols'] as $name => $symbol) {
                    $d = $this->fetchIndex($symbol);
                    if ($d) $marketData[$name] = $d;
                }
                $data['marketData'] = $marketData;
                $data['topGainers'] = $this->fetchStockList('day_gainers');
                $data['topLosers']  = $this->fetchStockList('day_losers');
                break;

            case 'commodities':
                $commodityData = [];
                foreach ($map['symbols'] as $symbol) {
                    $commodityData[$symbol] = $this->fetchIndex($symbol);
                }
                $data['commodityData'] = $commodityData;
                break;
        }
    }
 $cat = Catagories::where('slug', $category)->first();
    $subcat = Subcatagory::where('slug', $category)->first();

    $articleQuery = Articles::query()
        ->where('status', 'published')
        ->with(['media', 'author']); // eager load media & author

    if ($cat) {
        $articleQuery->where('category_id', $cat->id);
    } elseif ($subcat) {
        $articleQuery->where('subcategory_id', $subcat->id);
    }

    $articles = $articleQuery->orderByDesc('published_at')->get();
   // ----------------------------
// Fetch news (Indian + Global separately)
// ----------------------------
$newsData = $this->fetchNewsByCategory($category);

$indiaNews  = collect($newsData['india']);
$globalNews = collect($newsData['global']);

// Merge India first, then Global
$allNews = $indiaNews
            ->merge($globalNews)
            ->unique('url') // remove duplicates
            ->sortByDesc('published_at')
            ->values();

$perPage = 7;
$page = Paginator::resolveCurrentPage() ?: 1;

$paginatedNews = new LengthAwarePaginator(
    $allNews->forPage($page, $perPage),
    $allNews->count(),
    $perPage,
    $page,
    ['path' => Paginator::resolveCurrentPath()]
);

$data['news'] = $paginatedNews;
    // ----------------------------
    // Fetch related news for sidebar
    // ----------------------------
    $data['related'] = $this->fetchRelatedNews($category);

    // Return the category view
   return view('category', compact('category', 'data', 'articles'));
} 

}
