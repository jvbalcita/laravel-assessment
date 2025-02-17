<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Route::macro('softDeletes', function ($name, $controller) {
            Route::get("$name/trashed", [$controller, 'index'])->name("$name.trashed");
            Route::patch("$name/{id}/restore", [$controller, 'restore'])->name("$name.restore");
            Route::delete("$name/{id}/force", [$controller, 'delete'])->name("$name.delete");
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
