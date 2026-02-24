<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articles;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $editorId = session('user_id'); // currently logged-in editor

        // Fetch only articles uploaded by this editor
        $articles = Articles::where('author_id', $editorId)
            ->orderBy('title')
            ->get();

        $selectedArticleId = $request->get('article_id');

        $comments = [];
        if ($selectedArticleId) {
            // Ensure the article belongs to this editor
            $article = Articles::where('id', $selectedArticleId)
                ->where('author_id', $editorId)
                ->first();

            if ($article) {
                // Fetch comments for selected article with user info
                $comments = Comment::where('article_id', $selectedArticleId)
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('editor.comments', compact('articles', 'comments', 'selectedArticleId'));
    }
}