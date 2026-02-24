<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
class NewsController extends Controller
{
   public function index(Request $request)
{
    $endpoint = config('services.newsapi.endpoint');
    $apiKey   = config('services.newsapi.key');

    // Get current page from query string (default 1)
    $page = $request->get('page', 1);

    // Market news (main section) – 10 per page
    $indiaResponse = Http::get($endpoint, [ 
        'q'        => 'Indian stock market OR Sensex OR Nifty OR NSE OR BSE OR IPO OR shares OR trading OR company results',
        'pageSize' => 5,
        'page'     => $page,
        'sortBy'   => 'publishedAt',
        'language' => 'en',
        'apiKey'   => $apiKey,
    ]);

    $indiaNews = $indiaResponse->successful()
        ? $indiaResponse->json()['articles']
        : [];

    // Secondary market news (used as worldNews section)
    $worldResponse = Http::get($endpoint, [
        'q'        => 'world Stock Market OR corporate earnings OR quarterly results OR stock analysis OR market trends',
        'pageSize' => 5,
        'sortBy'   => 'publishedAt',
        'language' => 'en',
        'apiKey'   => $apiKey,
    ]);

    $worldNews = $worldResponse->successful()
        ? $worldResponse->json()['articles']
        : [];

    // Breaking market news (sidebar)
    $breakingResponse = Http::get($endpoint, [
        'q'        => 'Indian breaking stock news OR IPO launch OR Sensex crash OR market rally',
        'pageSize' => 10,
        'sortBy'   => 'relevancy',
        'language' => 'en',
        'apiKey'   => $apiKey,
    ]);

    $breakingNews = $breakingResponse->successful()
        ? $breakingResponse->json()['articles']
        : [];

    return view('news', [
        'indiaNews'     => $indiaNews,
        'worldNews'     => $worldNews,
        'breakingNews'  => $breakingNews,
        'page'          => $page,
    ]);
}
//E ARTICLE PAGE
  public function show($title)
{
    $endpoint = config('services.newsapi.endpoint');
    $apiKey   = config('services.newsapi.key');

    $decodedTitle = urldecode($title);

    // Fetch exact article
    $response = Http::get($endpoint, [
        'q'        => '"' . $decodedTitle . '"',
        'pageSize' => 1,
        'language' => 'en',
        'apiKey'   => $apiKey,
    ]);

    $article = null;

    if ($response->successful() && !empty($response->json()['articles'])) {
        $article = $response->json()['articles'][0];
    }

    if (!$article) {
        abort(404);
    }

    /*
    |--------------------------------------------------------------------------
    | Detect Category Based on Title
    |--------------------------------------------------------------------------
    */

    $categoryQuery = 'stock market'; // default

    if (stripos($decodedTitle, 'ipo') !== false) {
        $categoryQuery = 'IPO OR new listing OR public issue';
    } 
    elseif (stripos($decodedTitle, 'sensex') !== false || stripos($decodedTitle, 'nifty') !== false) {
        $categoryQuery = 'Sensex OR Nifty OR NSE OR BSE';
    } 
    elseif (stripos($decodedTitle, 'earnings') !== false || stripos($decodedTitle, 'results') !== false) {
        $categoryQuery = 'quarterly results OR earnings report OR company results';
    } 
    elseif (stripos($decodedTitle, 'share') !== false || stripos($decodedTitle, 'stock') !== false) {
        $categoryQuery = 'shares OR stock market OR trading';
    }

    /*
    |--------------------------------------------------------------------------
    | Fetch Similar News Based on Detected Category
    |--------------------------------------------------------------------------
    */

    $similarResponse = Http::get($endpoint, [
        'q'        => $categoryQuery,
        'pageSize' => 6,
        'sortBy'   => 'publishedAt',
        'language' => 'en',
        'apiKey'   => $apiKey,
    ]);

    $similarNews = $similarResponse->successful()
        ? $similarResponse->json()['articles']
        : [];

    return view('show', compact('article','similarNews'));
}
   
}
