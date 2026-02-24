<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class IpoController extends Controller
{
    private $baseUrl;
    private $accessToken;

    public function __construct()
    {
        $this->baseUrl = config('services.upstox.base_url', env('UPSTOX_BASE_URL'));
        $this->accessToken = env('UPSTOX_ACCESS_TOKEN');
    }

    /**
     * Fetch IPO data from Upstox
     */
    private function fetchUpstoxIpos()
    {
        try {
            // Use caching for 15 minutes
            return Cache::remember('upstox_ipos', 900, function() {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Accept' => 'application/json',
                ])->get($this->baseUrl . '/market/ipos'); // IPO endpoint

                if (!$response->successful()) {
                    return ['upcoming'=>[], 'live'=>[], 'recent'=>[]];
                }

                $data = $response->json()['data'] ?? [];

                // Map Upstox response to our keys
                return [
                    'upcoming' => $data['upcoming'] ?? [],
                    'live'     => $data['live'] ?? [],
                    'recent'   => $data['recent'] ?? [],
                ];
            });

        } catch (\Exception $e) {
            return ['upcoming'=>[], 'live'=>[], 'recent'=>[]];
        }
    }

    /**
     * Fetch IPO-related news from Upstox (market news)
     */
    private function fetchUpstoxNews()
    {
        try {
            return Cache::remember('upstox_ipo_news', 900, function() {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Accept' => 'application/json',
                ])->get($this->baseUrl . '/market/news', [
                    'category' => 'ipo',
                    'limit' => 5,
                ]);

                if (!$response->successful()) return [];

                $articles = $response->json()['data'] ?? [];

                return array_map(function($article) {
                    return [
                        'title' => $article['title'] ?? '',
                        'url' => $article['url'] ?? '#',
                        'source' => $article['source'] ?? 'Upstox',
                        'publishedAt' => $article['publishedAt'] ?? now()->toISOString(),
                    ];
                }, $articles);
            });

        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Show IPO page
     */
    public function index()
    {
        $ipos = $this->fetchUpstoxIpos();
        $news = $this->fetchUpstoxNews();

        // Ensure keys exist
        $ipos = array_merge(['upcoming'=>[], 'live'=>[], 'recent'=>[]], $ipos);
        $news = $news ?? [];

        return view('sensex.ipo', compact('ipos', 'news'));
    }
}