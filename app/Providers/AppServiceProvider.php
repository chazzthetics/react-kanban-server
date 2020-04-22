<?php

namespace App\Providers;

use App\Board;
use App\Column;
use App\Observers\BoardObserver;
use App\Observers\ColumnObserver;
use App\Observers\TaskObserver;
use App\Observers\UserObserver;
use App\Task;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Observers.
     *
     * @var array
     */
    protected $observers = [
        User::class => UserObserver::class,
        Board::class => BoardObserver::class,
        Column::class => ColumnObserver::class,
        Task::class => TaskObserver::class,
    ];

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
}
