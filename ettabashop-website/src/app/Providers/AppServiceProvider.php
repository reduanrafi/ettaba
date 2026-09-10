<?php

namespace App\Providers;

use App\Models\AnonymousOrder;
use App\Models\Order;
use App\Models\User;
use App\Observers\AnonymousOrderObserver;
use App\Observers\OrderObserver;
use App\Observers\UserObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
//use App\Models\Order;
//use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

use Illuminate\Http\Resources\Json\JsonResource;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('path.public', function() {
            return base_path('/');
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        URL::forceScheme('https');
        Schema::defaultStringLength(191); // for server issue
        View::share('local', app()->getLocale());
        JsonResource::withoutWrapping();
        Order::observe(OrderObserver::class);
        AnonymousOrder::observe(AnonymousOrderObserver::class);
        User::observe(UserObserver::class);
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach ($attributes as $attribute) {
                    $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                }
            });

            return $this;
        });
        Paginator::useBootstrap();
    }
}
