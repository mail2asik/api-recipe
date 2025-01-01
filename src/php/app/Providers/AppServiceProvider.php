<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;

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
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Blade::directive('dateTime', function ($dateTime) {
            return "<?php echo date('d-m-Y h:i:s A', strtotime(". $dateTime .")) ?>";
        });

        Blade::directive('date', function ($dateTime) {
            return "<?php echo date('d-m-Y', strtotime(". $dateTime .")) ?>";
        });

        Paginator::useBootstrap();
    }
}
