<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Quote;
use Carbon\Carbon;

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
        // Pobierz losowy cytat z bazy danych
        $quote = Quote::inRandomOrder()->first();

        // Udostępnij cytat we wszystkich widokach
        View::share('quote', $quote);
        Carbon::setLocale('pl');
    }
}
