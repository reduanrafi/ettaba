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
                $homeCategories = (clone $catQuery)->where('show_on_home', 1)->orderBy('id', 'asc')->take(7)->get();
                if ($homeCategories->isEmpty()) {
                    $homeCategories = $allCategoriesList->where('parent_id', 0)->take(7);
                }
            } else {
                $homeCategories = $allCategoriesList->where('parent_id', 0)->take(7);
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
        return $this->resolveAndRenderDetail($request, $request->slug, null);
    }

    public function sellerProductDetail(Request $request, $shop_slug, $product_code)
    {
        return $this->resolveAndRenderDetail($request, $product_code, $shop_slug);
    }

    protected function resolveAndRenderDetail(Request $request, $identifier, $shopSlug = null)
    {
        \App\Models\ProductReview::ensureSchemaColumns();

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
            $detailQuery->with(['productReviews' => function ($q) {
                $q->with('user')->orderByDesc('id');
            }])
                ->withCount('productReviews')
                ->withAvg('productReviews', 'rating');
        }

        $product = null;
        $rawId = trim((string) $identifier);
        $decodedId = urldecode($rawId);

        if (is_numeric($rawId)) {
            $product = (clone $detailQuery)->where('unique_id', $rawId)->first();
            if (!$product) {
                $product = (clone $detailQuery)->find((int) $rawId);
            }
        }

        if (!$product && $rawId !== '') {
            $product = (clone $detailQuery)->where('slug', $rawId)->first();
        }
        if (!$product && $decodedId !== '' && $decodedId !== $rawId) {
            $product = (clone $detailQuery)->where('slug', $decodedId)->first();
        }
        if (!$product && $rawId !== '') {
            $product = (clone $detailQuery)->where('unique_id', $rawId)->first();
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

        // Check review eligibility for logged-in customer (1 review per delivered purchase, editable, not deletable)
        $deliveredOrderCount = 0;
        $userReviews = collect([]);
        $canAddNewReview = false;
        $editableReview = null;

        if (Auth::check()) {
            try {
                $userId = Auth::id();
                $deliveredOrderCount = \App\Models\Order::where('user_id', $userId)
                    ->whereIn('status', ['delivered', 'completed', 'done'])
                    ->whereHas('orderItems', function ($q) use ($product) {
                        $q->where('product_id', $product->id);
                    })
                    ->count();

                if (Schema::hasColumn('product_reviews', 'user_id')) {
                    $userReviews = \App\Models\ProductReview::where('product_id', $product->id)
                        ->where('user_id', $userId)
                        ->orderByDesc('id')
                        ->get();
                }

                $canAddNewReview = ($deliveredOrderCount > $userReviews->count());
                $editableReview = $userReviews->first();
            } catch (\Throwable $e) {
            }
        }

        return view('website.detail', [
            'product' => $product,
            'sellerShop' => $sellerShop,
            'categories' => $categories,
            'deliveredOrderCount' => $deliveredOrderCount,
            'userReviews' => $userReviews,
            'canAddNewReview' => $canAddNewReview,
            'editableReview' => $editableReview,
        ]);
    }

    public function saveProductReview(Request $request)
    {
        if (!Auth::check()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'রিভিউ দেওয়ার জন্য অনুগ্রহ করে লগইন করুন।'], 401);
            }
            return redirect()->route('login');
        }

        \App\Models\ProductReview::ensureSchemaColumns();

        $validated = $request->validate([
            'product_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'review_message' => 'required|string|max:1000',
            'review_id' => 'nullable|integer',
        ]);

        $userId = Auth::id();
        $productId = (int) $validated['product_id'];
        $reviewId = !empty($validated['review_id']) ? (int) $validated['review_id'] : null;

        // If editing an existing review belonging to this user
        if ($reviewId) {
            $existing = \App\Models\ProductReview::where('id', $reviewId)
                ->where('product_id', $productId)
                ->where('user_id', $userId)
                ->first();

            if (!$existing) {
                $msg = 'আপনার রিভিউটি খুঁজে পাওয়া যায়নি।';
                return ($request->wantsJson() || $request->ajax())
                    ? response()->json(['status' => 'error', 'message' => $msg], 403)
                    : redirect()->back()->with('review_error', $msg);
            }

            $existing->update([
                'rating' => (int) $validated['rating'],
                'review_message' => trim($validated['review_message']),
            ]);

            $msg = 'আপনার রিভিউ সফলভাবে আপডেট করা হয়েছে!';
            return ($request->wantsJson() || $request->ajax())
                ? response()->json(['status' => 'success', 'message' => $msg, 'review' => $existing])
                : redirect()->back()->with('review_success', $msg);
        }

        // Check delivered orders for this product by this user
        $deliveredOrders = \App\Models\Order::where('user_id', $userId)
            ->whereIn('status', ['delivered', 'completed', 'done'])
            ->whereHas('orderItems', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->orderBy('id', 'asc')
            ->get();

        $deliveredCount = $deliveredOrders->count();
        if ($deliveredCount <= 0) {
            $msg = 'শুধুমাত্র এই পণ্যটি ক্রয় করে ডেলিভারি সফল হওয়ার পর আপনি রিভিউ দিতে পারবেন।';
            return ($request->wantsJson() || $request->ajax())
                ? response()->json(['status' => 'error', 'message' => $msg], 403)
                : redirect()->back()->with('review_error', $msg);
        }

        $existingReviews = \App\Models\ProductReview::where('product_id', $productId)
            ->where('user_id', $userId)
            ->orderBy('id', 'asc')
            ->get();

        if ($existingReviews->count() >= $deliveredCount) {
            // Update their latest review instead of creating a duplicate
            $latestReview = $existingReviews->last();
            $latestReview->update([
                'rating' => (int) $validated['rating'],
                'review_message' => trim($validated['review_message']),
            ]);

            $msg = 'আপনার রিভিউ সফলভাবে আপডেট করা হয়েছে!';
            return ($request->wantsJson() || $request->ajax())
                ? response()->json(['status' => 'success', 'message' => $msg, 'review' => $latestReview])
                : redirect()->back()->with('review_success', $msg);
        }

        $reviewedOrderIds = $existingReviews->pluck('order_id')->filter()->toArray();
        $targetOrder = $deliveredOrders->first(function ($ord) use ($reviewedOrderIds) {
            return !in_array($ord->id, $reviewedOrderIds);
        }) ?: $deliveredOrders->last();

        $newReview = \App\Models\ProductReview::create([
            'product_id' => $productId,
            'user_id' => $userId,
            'order_id' => $targetOrder ? $targetOrder->id : null,
            'rating' => (int) $validated['rating'],
            'review_message' => trim($validated['review_message']),
        ]);

        $msg = 'আপনার রিভিউ সফলভাবে জমা হয়েছে! ধন্যবাদ।';
        return ($request->wantsJson() || $request->ajax())
            ? response()->json(['status' => 'success', 'message' => $msg, 'review' => $newReview])
            : redirect()->back()->with('review_success', $msg);
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
