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

            if (auth()->check()) {
                $user = auth()->user();


                if ($user->role === 'guru') {
                    

                    $pendingCount = \App\Models\Proposal::where('status', 'pending')
                        ->where('guru_id', $user->id) 
                        ->count();

                    $view->with('pendingCount', $pendingCount);
                }
            }
        });
    }
}
