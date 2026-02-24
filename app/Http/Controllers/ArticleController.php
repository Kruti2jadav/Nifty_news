<?php
namespace App\Http\Controllers;

use App\Models\Articles;
use App\Models\Categories;
use App\Models\Subcategory;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Articles::where('status', 'published')
            ->with(['media', 'author'])
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('articles.index', compact('articles'));
    }
public function show($slug)
{
    // Fetch main article
    $article = Articles::with([
            'media',
            'author',
            'category',
            'subcategory'
        ])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

    // Increase views
    $article->increment('views');


    /*
    |--------------------------------------------------------------------------
    | Fetch Related Articles
    |--------------------------------------------------------------------------
    */

    $relatedArticles = Articles::with([
            'media',
            'author',
            'category',
            'subcategory'
        ])
        ->where('status', 'published')
        ->where('id', '!=', $article->id)
        ->where(function ($q) use ($article) {

            if (!is_null($article->category_id)) {
                $q->where('category_id', $article->category_id);
            }

            if (!is_null($article->subcategory_id)) {
                $q->orWhere('subcategory_id', $article->subcategory_id);
            }

        })
        ->orderByDesc('published_at')
        ->limit(5)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Fallback: Latest Articles if No Related Found
    |--------------------------------------------------------------------------
    */

    if ($relatedArticles->isEmpty()) {

        $relatedArticles = Articles::with([
                'media',
                'author',
                'category',
                'subcategory'
            ])
            ->where('status', 'published')
            ->where('id', '!=', $article->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();
    }

    return view('articles.show', compact('article', 'relatedArticles'));
}



}
