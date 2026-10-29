<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use Facades\App\Services\EarningStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    private $globalObject;
    private $moduleName = 'Shop';
    private $singularVariableName = 'shop';
    private $pluralVariableName = 'shops';

    public function __construct()
    {
        $this->globalObject = new Shop();
    }

    public function Index(Request $request)
    {
        $shops = Shop::orderByDesc('id')->get();

        return view('website.shops', [
            'shops' => $shops,
        ]);
    }

    public function search(Request $request)
    {
        $keywords = trim((string) $request->get('keywords', ''));
        $shops = $this->globalObject->SearchShop($keywords);

        return view('website.shops', [
            'shops' => $shops,
        ]);
    }

    public function detail(Request $request)
    {
        $id = $request->get('id');
        $shop = Shop::where('user_id', $id)
            ->orWhere('id', $id)
            ->orWhere('unique_id', $id)
            ->first();

        if (!$shop) {
            abort(404, 'সেলার শপ খুঁজে পাওয়া যায়নি।');
        }

        // Redirect legacy /shop-detail?id=... to custom URL /{shop_slug}
        $slug = $shop->url_slug;
        if (!empty($slug)) {
            $query = $request->except('id');
            $targetUrl = url('/' . $slug) . (!empty($query) ? '?' . http_build_query($query) : '');
            return redirect()->to($targetUrl);
        }

        return $this->renderSellerShopPage($shop, $request);
    }

    public function showBySlug(Request $request, $shop_slug)
    {
        $shop = $this->resolveShopBySlug($shop_slug);

        if (!$shop) {
            abort(404, 'সেলার শপ খুঁজে পাওয়া যায়নি।');
        }

        return $this->renderSellerShopPage($shop, $request);
    }

    public function sellerSectionProducts(Request $request, $shop_slug, $section_type)
    {
        $shop = $this->resolveShopBySlug($shop_slug);

        if (!$shop) {
            abort(404, 'সেলার শপ খুঁজে পাওয়া যায়নি।');
        }

        $request->merge(['section' => $section_type]);
        return $this->renderSellerShopPage($shop, $request);
    }

    protected function resolveShopBySlug($shopSlug)
    {
        $decoded = trim(urldecode((string) $shopSlug));
        if ($decoded === '') {
            return null;
        }

        $normalizedSpace = str_replace(['_', '-'], ' ', $decoded);
        $normalizedDash = str_replace(['_', ' '], '-', $decoded);
        $normalizedUnderscore = str_replace(['-', ' '], '_', $decoded);

        $shop = Shop::whereRaw('LOWER(slug) = ?', [strtolower($decoded)])
            ->orWhereRaw('LOWER(slug) = ?', [strtolower($normalizedUnderscore)])
            ->orWhereRaw('LOWER(slug) = ?', [strtolower($normalizedDash)])
            ->orWhereRaw('LOWER(name_en) = ?', [strtolower($decoded)])
            ->orWhereRaw('LOWER(name_en) = ?', [strtolower($normalizedSpace)])
            ->orWhere('name_bn', $decoded)
            ->orWhere('name_bn', $normalizedSpace)
            ->first();

        if (!$shop && is_numeric($decoded)) {
            $shop = Shop::where('unique_id', (int) $decoded)
                ->orWhere('user_id', (int) $decoded)
                ->orWhere('id', (int) $decoded)
                ->first();
        }

        return $shop;
    }

    protected function buildSellerProductQuery($ownerId, $sectionType = 'all_products', $searchQuery = '')
    {
        $query = Product::where('owner_id', $ownerId)
            ->where('is_deleted', 0)
            ->withCount('productReviews')
            ->withAvg('productReviews', 'rating');

        if ($searchQuery !== '') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name_en', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('name_bn', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('unique_id', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('slug', 'LIKE', "%{$searchQuery}%");
                if (is_numeric($searchQuery)) {
                    $q->orWhere('id', (int) $searchQuery);
                }
            });
        }

        switch ($sectionType) {
            case 'most_sold':
                $query->orderByRaw('COALESCE(sold_amount, 0) DESC, id DESC');
                break;

            case 'high_cashback':
                $query->orderByRaw('CAST(REPLACE(COALESCE(tcb_en, "0"), ",", "") AS DECIMAL(10,2)) DESC, COALESCE(sold_amount, 0) DESC, id DESC');
                break;

            case 'high_point':
                $query->orderByRaw('CAST(REPLACE(COALESCE(trp_en, "0"), ",", "") AS DECIMAL(10,2)) DESC, COALESCE(sold_amount, 0) DESC, id DESC');
                break;

            case 'new_products':
                $query->orderByDesc('id');
                break;

            case 'all_products':
            default:
                $query->orderByDesc('id');
                break;
        }

        return $query;
    }

    protected function renderSellerShopPage(Shop $shop, Request $request)
    {
        $ownerId = $shop->user_id;
        $sellerName = $shop->name_en ?: $shop->name_bn;
        $searchQuery = trim((string) $request->get('q', ''));
        $activeSection = trim((string) $request->get('section', ''));

        $statistics = [];
        if (Auth::check()) {
            try {
                $statistics = EarningStatisticsService::EarningStatistics(Auth::id());
            } catch (\Throwable $e) {
                $statistics = [];
            }
        }

        $sectionDefinitions = [
            'most_sold' => [
                'key' => 'most_sold',
                'title' => $sellerName . '-এর সবচেয়ে বিক্রিত পণ্য',
                'badge_type' => 'fire',
                'limit' => 4,
            ],
            'high_cashback' => [
                'key' => 'high_cashback',
                'title' => $sellerName . '-এর অধিক ক্যাশব্যাকের পণ্য',
                'badge_type' => 'percent',
                'limit' => 2,
            ],
            'high_point' => [
                'key' => 'high_point',
                'title' => $sellerName . '-এর অধিক পয়েন্টের পণ্য',
                'badge_type' => 'dollar',
                'limit' => 2,
            ],
            'new_products' => [
                'key' => 'new_products',
                'title' => $sellerName . '-এর নতুন পণ্য',
                'badge_type' => 'new',
                'limit' => 2,
            ],
            'all_products' => [
                'key' => 'all_products',
                'title' => $sellerName . '-এর সকল পণ্য',
                'badge_type' => 'grid',
                'limit' => 8,
            ],
        ];

        // If searching within this seller's shop or viewing a specific section ("সব দেখুন")
        if ($searchQuery !== '' || ($activeSection !== '' && isset($sectionDefinitions[$activeSection]))) {
            $sectionKey = isset($sectionDefinitions[$activeSection]) ? $activeSection : 'all_products';
            $filteredProducts = $this->buildSellerProductQuery($ownerId, $sectionKey, $searchQuery)->paginate(16);

            if ($request->ajax() || $request->boolean('ajax')) {
                $html = '';
                foreach ($filteredProducts as $product) {
                    $html .= '<div class="col-6 col-md-4 col-lg-3 px-1 mb-2">';
                    $html .= view('website.layouts.home_sections.modern_product_item', ['product' => $product])->render();
                    $html .= '</div>';
                }
                return response()->json([
                    'html' => $html,
                    'hasMore' => $filteredProducts->hasMorePages(),
                    'nextPage' => $filteredProducts->currentPage() + 1,
                ]);
            }

            $filterTitle = $searchQuery !== ''
                ? '"' . $searchQuery . '" এর অনুসন্ধান ফলাফল (' . $sellerName . ')'
                : $sectionDefinitions[$sectionKey]['title'];

            return view('website.shop_detail', [
                'shop' => $shop,
                'sellerName' => $sellerName,
                'statistics' => $statistics,
                'isFilteredView' => true,
                'filterTitle' => $filterTitle,
                'activeSectionMeta' => $sectionDefinitions[$sectionKey] ?? $sectionDefinitions['all_products'],
                'searchQuery' => $searchQuery,
                'products' => $filteredProducts,
                'sellerSections' => [],
                'hideGlobalSearch' => true,
            ]);
        }

        // Main Seller Shop View with 5 Distinct Sections in Exact Order
        $sellerSections = [];
        foreach ($sectionDefinitions as $key => $def) {
            try {
                $secProducts = $this->buildSellerProductQuery($ownerId, $key)->take($def['limit'])->get();
            } catch (\Throwable $e) {
                $secProducts = collect([]);
            }
            $sellerSections[] = array_merge($def, [
                'products' => $secProducts,
            ]);
        }

        return view('website.shop_detail', [
            'shop' => $shop,
            'sellerName' => $sellerName,
            'statistics' => $statistics,
            'isFilteredView' => false,
            'searchQuery' => '',
            'products' => collect([]),
            'sellerSections' => $sellerSections,
            'hideGlobalSearch' => true,
        ]);
    }
}
