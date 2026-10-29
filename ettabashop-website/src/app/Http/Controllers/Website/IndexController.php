<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Slider;
use App\Models\TopType;
use App\Services\ProductService;
use App\Services\RewardService;
use Facades\App\Services\EarningStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $productService = new ProductService();
        $statistics = [];
        $userPoints = 0;
        $nextReward = null;

        try {
            $shops = Shop::paginate(10);
        } catch (\Throwable $e) {
            $shops = collect([]);
        }

        try {
            $sliderQuery = Slider::query();
            if (Schema::hasColumn('sliders', 'is_active')) {
                $sliderQuery->where('is_active', 1);
            }
            if (Schema::hasColumn('sliders', 'sort_order')) {
                $sliderQuery->orderBy('sort_order', 'asc');
            }
            $slider = $sliderQuery->orderBy('id', 'desc')->get();
        } catch (\Throwable $e) {
            $slider = collect([]);
        }

        $category = new Category();
        try {
            $categories = CategoryResource::collection($category->GetCategoriesWithChild());
        } catch (\Throwable $e) {
            $categories = collect([]);
        }

        // Home categories & All categories modal list
        try {
            $catQuery = Category::where('is_deleted', 0);
            if (Schema::hasColumn('categories', 'is_active')) {
                $catQuery->where('is_active', 1);
            }
            if (Schema::hasColumn('categories', 'priority')) {
                $catQuery->orderBy('priority', 'asc');
            }
            $allCategoriesList = (clone $catQuery)->orderBy('id', 'asc')->get();

            if (Schema::hasColumn('categories', 'show_on_home')) {
                $homeCategories = (clone $catQuery)->where('show_on_home', 1)->orderBy('id', 'asc')->take(6)->get();
                if ($homeCategories->isEmpty()) {
                    $homeCategories = $allCategoriesList->where('parent_id', 0)->take(6);
                }
            } else {
                $homeCategories = $allCategoriesList->where('parent_id', 0)->take(6);
            }
        } catch (\Throwable $e) {
            $homeCategories = collect([]);
            $allCategoriesList = collect([]);
        }

        $products = $productService->GetHomeProducts();
        $homeSectionsData = $productService->getHomeSectionsWithProducts();

        if (Auth::check()) {
            try {
                $statistics = EarningStatisticsService::EarningStatistics(Auth::id());
            } catch (\Throwable $e) {
                $statistics = [];
            }

            try {
                $rewardService = app(RewardService::class);
                $userPoints = $rewardService->getUserPoints(Auth::user());
                $nextReward = $rewardService->getNextRewardForUser(Auth::user());
            } catch (\Throwable $e) {
                $userPoints = 0;
                $nextReward = null;
            }
        }

        return view('website.index', [
            'sliders' => $slider,
            'categories' => $categories,
            'homeCategories' => $homeCategories,
            'allCategoriesList' => $allCategoriesList,
            'homeSectionsData' => $homeSectionsData,
            'shops' => $shops,
            'statistics' => $statistics,
            'userPoints' => $userPoints,
            'nextReward' => $nextReward,

            'featured' => $products['featured'] ?? [],
            'newArrival' => $products['new'] ?? [],
            'mostPoint' => $products['mostPoint'] ?? [],
            'dailyNeeds' => $products['dailyNeeds'] ?? [],
            'fashion' => $products['fashion'] ?? [],
            'library' => $products['library'] ?? [],
            'jewelry' => $products['jewelry'] ?? [],
            'electronicGoods' => $products['electronicGoods'] ?? [],
            'electronicAccessories' => $products['electronicAccessories'] ?? [],
        ]);
    }

    public function detail(Request $request)
    {
        $category = new Category();
        try {
            $categories = CategoryResource::collection($category->GetCategoriesWithChild());
        } catch (\Throwable $e) {
            $categories = collect([]);
        }

        $productService = new ProductService();
        $canReviews = $productService->canLoadProductReviews();

        $detailQuery = Product::with(['category', 'productImages']);
        if ($canReviews) {
            $detailQuery->with('productReviews')
                ->withCount('productReviews')
                ->withAvg('productReviews', 'rating');
        }

        $product = (clone $detailQuery)->where('slug', $request->slug)->first();

        if (!$product && is_numeric($request->slug)) {
            $product = (clone $detailQuery)->find((int) $request->slug);
        }

        if (!$product) {
            abort(404);
        }

        $sellerShop = null;
        if (!empty($product->owner_id)) {
            try {
                $sellerShop = Shop::where('user_id', $product->owner_id)->first();
            } catch (\Throwable $e) {
                $sellerShop = null;
            }
        }

        return view('website.detail', [
            'product' => $product,
            'sellerShop' => $sellerShop,
            'categories' => $categories,
        ]);
    }

    public function products(Request $request)
    {
        $category = new Category();
        $categories = CategoryResource::collection($category->GetCategoriesWithChild());
        $products = $category->GetProductsByCategory($request->slug);

        return view('website.products', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function search(Request $request)
    {
        $keywords = trim((string) $request->get('keywords', ''));
        $category = new Category();
        try {
            $categories = CategoryResource::collection($category->GetCategoriesWithChild());
        } catch (\Throwable $e) {
            $categories = collect([]);
        }

        $matchedShops = collect([]);
        $matchedOwnerIds = [];

        if ($keywords !== '') {
            try {
                $shopQuery = Shop::query()
                    ->where(function ($q) use ($keywords) {
                        $q->where('name_en', 'LIKE', "%{$keywords}%")
                          ->orWhere('name_bn', 'LIKE', "%{$keywords}%")
                          ->orWhere('unique_id', 'LIKE', "%{$keywords}%");
                        if (Schema::hasColumn('shops', 'slug')) {
                            $q->orWhere('slug', 'LIKE', "%{$keywords}%");
                        }
                        if (is_numeric($keywords)) {
                            $q->orWhere('id', (int) $keywords)
                              ->orWhere('user_id', (int) $keywords);
                        }
                    });
                $matchedShops = $shopQuery->take(12)->get();
                $matchedOwnerIds = $matchedShops->pluck('user_id')->filter()->unique()->toArray();
            } catch (\Throwable $e) {
                $matchedShops = collect([]);
            }
        }

        $productService = new ProductService();
        $productQuery = Product::query()->where('is_deleted', 0);
        if ($productService->canLoadProductReviews()) {
            $productQuery->withCount('productReviews')
                         ->withAvg('productReviews', 'rating');
        }

        if ($keywords !== '') {
            $productQuery->where(function ($q) use ($keywords, $matchedOwnerIds) {
                $q->where('name_en', 'LIKE', "%{$keywords}%")
                  ->orWhere('name_bn', 'LIKE', "%{$keywords}%")
                  ->orWhere('unique_id', 'LIKE', "%{$keywords}%")
                  ->orWhere('slug', 'LIKE', "%{$keywords}%");

                if (is_numeric($keywords)) {
                    $q->orWhere('id', (int) $keywords)
                      ->orWhere('owner_id', (int) $keywords);
                }

                if (!empty($matchedOwnerIds)) {
                    $q->orWhereIn('owner_id', $matchedOwnerIds);
                }
            });
        }

        $products = $productQuery->orderByDesc('id')->paginate(16);

        if ($request->ajax() || $request->boolean('ajax')) {
            $html = '';
            foreach ($products as $product) {
                $html .= '<div class="col-6 col-md-4 col-lg-3 px-1 mb-2">';
                $html .= view('website.layouts.home_sections.modern_product_item', ['product' => $product])->render();
                $html .= '</div>';
            }
            return response()->json([
                'html' => $html,
                'hasMore' => $products->hasMorePages(),
                'nextPage' => $products->currentPage() + 1,
            ]);
        }

        return view('website.search', [
            'products' => $products,
            'matchedShops' => $matchedShops,
            'keywords' => $keywords,
            'categories' => $categories,
        ]);
    }

    public function profile($id)
    {
        $category = new Category();
        $categories = CategoryResource::collection($category->GetCategoriesWithChild());
        $product = Product::where('id', $id)->first();

        return view('website.profile', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function about()
    {
        return view('website.about');
    }

    public function contact()
    {
        return view('website.contact');
    }
}
