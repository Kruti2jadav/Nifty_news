<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articles;
use App\Models\Catagories; 
use App\Models\PageVisit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
       
         $fixedCategories = [
            ['name' => 'Markets',   'url' => url('/')],
            ['name' => 'Companies', 'url' => url('/')],
            ['name' => 'News',      'url' => url('/news')],
            ['name' => 'Calculators','url' => url('/')],
        ];

          // Attach subcategories to fixed categories if they exist in DB
    $subcats = Catagories::with('subcategories')
        ->whereIn('name', ['Markets','Companies'])
        ->get()
        ->keyBy('name');

    foreach ($fixedCategories as &$cat) {
        if (isset($subcats[$cat['name']])) {
            $cat['subcategories'] = $subcats[$cat['name']]->subcategories;
        } else {
            $cat['subcategories'] = collect();
        }
    }

    // All dynamic categories for sidebar only
    $allCategories = Catagories::with('subcategories')
        ->orderBy('name')
        ->get();

    return view('welcome', [
        'fixedCategories' => $fixedCategories, // navbar
        'allCategories'   => $allCategories,   // sidebar only
    ]);
    } 
   
}
