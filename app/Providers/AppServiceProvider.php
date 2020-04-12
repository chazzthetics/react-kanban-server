<?php

namespace App\Providers;

use App\Board;
use App\Column;
use App\Observers\BoardObserver;
use App\Observers\ColumnObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Board::observe(BoardObserver::class);
        Column::observe(ColumnObserver::class);
    }
}
