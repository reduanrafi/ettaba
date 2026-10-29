<?php

namespace App\Providers;

use App\Models\AnonymousOrder;
use App\Models\Category;
use App\Models\Order;
use App\Models\Profile;
use App\Models\PublicNotice;
use App\Models\Upazila;
use App\Models\District;
use App\Models\User;
use App\Models\UserNotification;
use App\Observers\AnonymousOrderObserver;
use App\Observers\OrderObserver;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        $this->app->bind('path.public', function () {
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
        Schema::defaultStringLength(191);
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

        // Share header location & notification count globally across website views
        View::composer(['website.layouts.includes.header', 'website.layouts.layout'], function ($view) {
            $headerLocationText = null;
            $unreadNoticeCount = 0;

            try {
                if (Auth::check()) {
                    $user = Auth::user();
                    $profile = Profile::where('user_id', $user->id)->first();
                    $areaName = '';
                    $wardNo = '';

                    if ($profile) {
                        $areaName = trim($profile->union_name ?? '');
                        $wardNo = trim($profile->ward_no ?? '');
                    }

                    if (empty($areaName) && !empty($user->upazila_id)) {
                        $upz = Upazila::find($user->upazila_id);
                        if ($upz) {
                            $areaName = $upz->bn_name ?: $upz->name;
                        }
                    }

                    if (empty($areaName) && !empty($user->district_id)) {
                        $dist = District::find($user->district_id);
                        if ($dist) {
                            $areaName = $dist->bn_name ?: $dist->name;
                        }
                    }

                    if ($profile && empty($areaName) && !empty($profile->address)) {
                        // Extract from comma-separated address if available
                        $parts = array_map('trim', explode(',', $profile->address));
                        if (count($parts) >= 2) {
                            $areaName = $parts[1];
                        } else {
                            $areaName = $parts[0];
                        }
                    }

                    $enDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                    $bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

                    if (!empty($areaName)) {
                        if (!empty($wardNo)) {
                            // Strip non-digit prefix if stored like "ওয়ার্ড নং: 7"
                            $cleanWard = preg_replace('/^.*[:\s]/u', '', $wardNo);
                            $wardBn = str_replace($enDigits, $bnDigits, $cleanWard ?: $wardNo);
                            $headerLocationText = $areaName . '/' . $wardBn;
                        } else {
                            $headerLocationText = $areaName;
                        }
                    }
                }
            } catch (\Throwable $e) {
                $headerLocationText = null;
            }

            try {
                // Public notices count (for both logged-in and logged-out users)
                if (Schema::hasTable('public_notices')) {
                    $readIds = session('read_public_notices', []);
                    if (!is_array($readIds)) {
                        $readIds = [];
                    }
                    $cookieIds = json_decode(request()->cookie('read_public_notices', '[]'), true);
                    if (is_array($cookieIds)) {
                        $readIds = array_unique(array_merge($readIds, $cookieIds));
                    }

                    $publicQuery = PublicNotice::where('is_published', 1);
                    if (!empty($readIds)) {
                        $publicQuery->whereNotIn('id', $readIds);
                    }
                    $unreadNoticeCount += $publicQuery->count();
                }

                // Also add personal unread notifications if logged in
                if (Auth::check() && Schema::hasTable('user_notifications')) {
                    $unreadNoticeCount += UserNotification::where('user_id', Auth::id())
                        ->where('is_read', 0)
                        ->count();
                }
            } catch (\Throwable $e) {
                // Fallback
            }

            $view->with('headerLocationText', $headerLocationText);
            $view->with('unreadNoticeCount', $unreadNoticeCount);
        });
    }
}
