<?php

namespace App\Providers;

use App\Board;
use App\Column;
use App\Observers\BoardObserver;
use App\Observers\ColumnObserver;
use App\Observers\TaskObserver;
use App\Task;
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
        foreach ($this->observers as $model => $observer) {
            $model::observe($observer);
        }
    }

    /**
     * Observers
     *
     * @var array
     */
    protected $observers = [
        Board::class => BoardObserver::class,
        Column::class => ColumnObserver::class,
        Task::class => TaskObserver::class,
    ];
}
