<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class EditorDashboardController extends Controller
{
    public function index()
    {
        $editorId = session('user_id');

        // My Articles
        $totalArticles = DB::table('articles')
            ->where('author_id', $editorId)
            ->count();

        // Published
        $published = DB::table('articles')
            ->where('author_id', $editorId)
            ->where('status', 'published')
            ->count();

        // Drafts
        $drafts = DB::table('articles')
            ->where('author_id', $editorId)
            ->where('status', 'draft')
            ->count();

        // Total Views
        $totalViews = DB::table('articles')
            ->where('author_id', $editorId)
            ->sum('views');

        // Pending Review (drafts)
        $pendingReview = $drafts;

        return view('editor.dashboard', compact(
            'totalArticles',
            'published',
            'drafts',
            'totalViews',
            'pendingReview'
        ));
    }
}