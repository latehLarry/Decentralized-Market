<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\{Schema,Blade};
use Illuminate\Support\Collection;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Blade::if('seller', function() {
            return auth()->user()->isSeller();
        });

        Blade::if('staff', function() {
            return auth()->user()->isModerator() || auth()->user()->isAdmin();
        });

        Blade::if('admin', function() {
            return auth()->user()->isAdmin();
        });

        Blade::if('browsing', function(Category $category) {
            $lastPartUrl = request()->segment(count(request()->segments()));

            if ($category->slug === $lastPartUrl) {
                return true;
            }

            foreach ($category->allSubcategories() as $subcategory) {
                if ($subcategory->slug === $lastPartUrl) {
                    return true;
                }    
            }

            return false;
        });

        /**
         * Paginate a standard Laravel Collection.
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * 
         * @return array
         */
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
