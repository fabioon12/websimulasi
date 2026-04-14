<?php

namespace App\Providers;

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
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            if (auth()->check() && auth()->user()->role === 'guru') {
         
                $pendingCount = \App\Models\Proposal::where('status', 'pending')->count();
                $view->with('pendingCount', $pendingCount);
            }
        });
    }
}
