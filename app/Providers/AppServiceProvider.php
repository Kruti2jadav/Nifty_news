<?php

namespace App\Providers;
use App\Models\Articles;
use App\Models\Catagories; 
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('*', function ($view) {

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

        // Get all other dynamic categories from DB that are NOT fixed
        $allCategories = Catagories::with('subcategories')
        ->orderBy('name')
        ->get();

            $view->with(compact('fixedCategories','allCategories'));
        });
    }
}
