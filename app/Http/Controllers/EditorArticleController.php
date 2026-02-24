<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Catagories;
use App\Models\Subcatagory;
use App\Models\Articles;
use App\Models\ArticleMedia;

class EditorArticleController extends Controller
{
    public function index()
    {
        $articles = Articles::where('author_id', session('user_id'))
            ->latest()
            ->get();

        $categories = Catagories::where('status', 1)->get();

        return view('editor.articles', compact('articles', 'categories'));
    }

    public function getSubcategories($categoryId)
{
    $subcategories = Subcatagory::where('category_id', $categoryId)->get();
    return response()->json($subcategories);
}

    public function getMedia($id)
    {
        return response()->json(
            ArticleMedia::where('article_id', $id)->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|max:255',
            'status' => 'required|in:draft,published,archived',
        ]);

        $article = Articles::create([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'author_id' => session('user_id'),
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'short_description' => $request->short_description,
            'full_content' => $request->full_content,
            'language' => $request->language,
            'status' => $request->status,
            'is_breaking' => $request->is_breaking ?? 0,
            'is_trending' => $request->is_trending ?? 0,
        ]);

    if ($request->hasFile('media')) {

    foreach ($request->file('media') as $file) {

        $mime = $file->getMimeType();

        // Decide folder + type
        if (str_starts_with($mime, 'image/')) {
            $folder = 'images/news';
            $type   = 'image';
        } elseif (str_starts_with($mime, 'video/')) {
            $folder = 'video';
            $type   = 'video';
        } elseif (str_starts_with($mime, 'audio/')) {
            $folder = 'audio';
            $type   = 'audio';
        } else {
            $folder = 'document';
            $type   = 'document';
        }

        // Create folder if not exists
        $destinationPath = public_path($folder);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Move file to public folder
        $file->move($destinationPath, $filename);

        // Save relative path in DB
        ArticleMedia::create([
            'article_id' => $article->id,
            'type'       => $type,
            'file_url'   => $folder . '/' . $filename, // ex: images/news/12345.jpg
            'size'       => $file->getSize(),
        ]);
    }
}

        return back()->with('success', 'Article Added');
    }

    public function update(Request $request, $id)
    {
        $article = Articles::findOrFail($id);

        $article->update([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'short_description' => $request->short_description,
            'full_content' => $request->full_content,
            'language' => $request->language,
            'status' => $request->status,
            'is_breaking' => $request->is_breaking ?? 0,
            'is_trending' => $request->is_trending ?? 0,
        ]);

     
// Handle file uploads
   if ($request->hasFile('media')) {

    // 1. Delete existing media first
    $existingMedia = ArticleMedia::where('article_id', $article->id)->get();

    foreach ($existingMedia as $media) {
        $filePath = public_path($media->file_url);
        if (file_exists($filePath)) {
            unlink($filePath); // Delete the file
        }
        $media->delete(); // Remove record from database
    }

    // 2. Add new media
    foreach ($request->file('media') as $file) {
        $mime = $file->getMimeType();

        // Decide folder + type
        if (str_starts_with($mime, 'image/')) {
            $folder = 'images/news';
            $type   = 'image';
        } elseif (str_starts_with($mime, 'video/')) {
            $folder = 'video';
            $type   = 'video';
        } elseif (str_starts_with($mime, 'audio/')) {
            $folder = 'audio';
            $type   = 'audio';
        } else {
            $folder = 'document';
            $type   = 'document';
        }

        // Create folder if not exists
        $destinationPath = public_path($folder);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Get size BEFORE moving
        $size = $file->getSize();

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Move file to public folder
        $file->move($destinationPath, $filename);

        // Save media record
        ArticleMedia::create([
            'article_id' => $article->id,
            'type'       => $type,
            'file_url'   => $folder . '/' . $filename,
            'size'       => $size,
        ]);
    }
}
     // Return back with success message
    return redirect()->back()->with('success', 'Article edited successfully.');
    }
    public function delete($id)
    {
        Articles::where('id', $id)->delete();
        return back();
    }

    public function deleteMedia($id)
    {
        $media = ArticleMedia::findOrFail($id);
        Storage::disk('public')->delete($media->file_url);
        $media->delete();

        return response()->json(['success' => true]);
    }
}