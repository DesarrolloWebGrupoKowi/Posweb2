<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\MenuPosweb;
use \Illuminate\Support\Facades\View;
use \Illuminate\Support\Facades\DB;

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
        Paginator::useBootstrap();
        //setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');
        setlocale(LC_TIME, "spanish");
        
        //$MenusCatalogos = DB::table('CatMenus')
        //                   ->where('IdTipoMenu', 1)
        //                    ->get();
        //view()->share('MenusCatalogos', $MenusCatalogos);

        //$MenusDatos = DB::table('CatMenus')
        //               ->where('IdTipoMenu', 2)
        //               ->get();
        //view()->share('MenusDatos', $MenusDatos);

        //$tiposMenu = DB::table('CatTipoMenu')
        //                    ->get();
        //view()->share('tiposMenu', $tiposMenu);
        
    }
}
