<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArticleMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ArticleMediaController extends Controller
{
    public function index()
    {
        $userId = session('user_id'); // or Auth::id();

        $mediaItems = ArticleMedia::whereHas('article', function($query) use ($userId) {
            $query->where('author_id', $userId);
        })->with('article')
          ->orderBy('article_id')
          ->get();

        return view('editor.media', compact('mediaItems'));
    }

    // Delete media
    public function destroy($id)
    {
        $media = ArticleMedia::findOrFail($id);

        // Delete the physical file
        if (File::exists(public_path($media->file_url))) {
            File::delete(public_path($media->file_url));
        }

        $media->delete();

        return redirect()->back()->with('success', 'Media deleted successfully.');
    }

    // Update media
    public function update(Request $request, $id)
{
    $media = ArticleMedia::findOrFail($id);

    if ($request->hasFile('media_file')) {

       $file = $request->file('media_file');

// Get size BEFORE moving
$size = $file->getSize();

// Decide folder dynamically
$mime = $file->getMimeType() ?? 'application/octet-stream';
if (str_starts_with($mime, 'image/')) {
    $folder = 'images/news';
    $type = 'image';
} elseif (str_starts_with($mime, 'video/')) {
    $folder = 'videos/news';
    $type = 'video';
} elseif (str_starts_with($mime, 'audio/')) {
    $folder = 'audio/news';
    $type = 'audio';
} else {
    $folder = 'documents/news';
    $type = 'document';
}

// Create folder if not exists
if (!file_exists(public_path($folder))) {
    mkdir(public_path($folder), 0755, true);
}

// Generate filename and move
$filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
$file->move(public_path($folder), $filename);

// Update DB record
$media->file_url = $folder . '/' . $filename;
$media->type = $type;
$media->size = $size; // Use the size captured before moving
$media->save();
        }
          return redirect()->back()->with('success', 'Media updated successfully.');
    }

  
}
