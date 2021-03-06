<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use DB;
use Log;
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

        if(!$this->app->environment('production')) {
            DB::listen(function ($query) {
                Log::info([
                    $query->sql,
                    $query->bindings,
                    $query->time
                ]);
            });
        }
    }
}
