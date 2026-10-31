<?php

namespace App\Providers;

use App\Models\HandCashOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Observers\HandCashOrderObserver;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
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
        //URL::forceScheme('https');
        Schema::defaultStringLength(191); // for server issue

        JsonResource::withoutWrapping();

        Order::observe(OrderObserver::class);
        HandCashOrder::observe(HandCashOrderObserver::class);

        Product::observe(ProductObserver::class);

        User::observe(UserObserver::class);

        // Ensure upload directories created with legacy 666 mode have proper 0755 permissions on Linux
        try {
            $rewardDirs = [
                base_path('../assets/images/rewards'),
                base_path('assets/images/rewards'),
                public_path('assets/images/rewards'),
            ];
            foreach ($rewardDirs as $rDir) {
                if (is_dir($rDir)) {
                    @chmod($rDir, 0755);
                    foreach (@scandir($rDir) ?: [] as $f) {
                        if ($f !== '.' && $f !== '..' && is_file($rDir . '/' . $f)) {
                            @chmod($rDir . '/' . $f, 0644);
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore permission check errors
        }

        Builder::macro('whereLike', function ($attributes, string $searchTerm) {

            $this->where(function (Builder $query) use ($attributes, $searchTerm) {

                foreach ($attributes as $attribute) {

                    $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                }
            });

            return $this;
        });
    }
}
