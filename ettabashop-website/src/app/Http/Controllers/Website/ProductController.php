<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\TopType;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function CategoryProducts(Request $request)
    {
        $category = new Category();
        $parentCategory = Category::where('slug', $request->slug)->first();

        if (!$parentCategory && is_numeric($request->slug)) {
            $parentCategory = Category::find((int) $request->slug);
        }

        if ($parentCategory) {
            $productService = new ProductService();
            $categoryIds = $productService->getCategoryAndChildrenIds($parentCategory->id);
            $products = Product::whereIn('category_id', $categoryIds)
                ->where('is_deleted', 0)
                ->withCount('productReviews')
                ->withAvg('productReviews', 'rating')
                ->orderByDesc('id')
                ->paginate(16);
            $categoryBySlug = $category->ChildCategories($parentCategory->id);
        } else {
            $parentCategory = (object) [
                'id' => 0,
                'name_bn' => str_replace('-', ' ', $request->slug),
                'name_en' => ucwords(str_replace('-', ' ', $request->slug)),
                'slug' => $request->slug,
            ];
            $products = Product::where('is_deleted', 0)
                ->withCount('productReviews')
                ->withAvg('productReviews', 'rating')
                ->orderByDesc('id')
                ->paginate(16);
            $categoryBySlug = collect([]);
        }

        if ($request->ajax() || $request->boolean('ajax')) {
            return $this->renderInfiniteScrollJson($products);
        }

        $parents = $category->ParentCategories();

        return view('website.category_products', [
            'products' => $products,
            'parent' => $parentCategory,
            'parents' => $parents,
            'categories' => $categoryBySlug,
        ]);
    }

    public function sectionProducts(Request $request, $slug)
    {
        $productService = new ProductService();
        $section = $productService->findSectionBySlug($slug);

        if (!$section) {
            $section = (object) [
                'title_bn' => 'সকল পণ্য',
                'title_en' => 'All Products',
                'slug' => $slug,
                'section_type' => 'all_products',
                'icon_class' => 'fas fa-th-large',
                'icon_color' => '#15803d',
            ];
        }

        $products = $productService->buildSectionProductQuery($section)->paginate(16);

        if ($request->ajax() || $request->boolean('ajax')) {
            return $this->renderInfiniteScrollJson($products);
        }

        return view('website.all_products', [
            'section' => $section,
            'pageTitle' => $section->title_bn ?: $section->title_en,
            'products' => $products,
        ]);
    }

    public function AllProducts(Request $request)
    {
        $productService = new ProductService();
        $type = $request->get('type', 'all');
        $pageTitle = 'সকল পণ্য';

        if ($type === 'mostPoint' || $type === 'high_point') {
            $pageTitle = 'অধিক পয়েন্টের পণ্য';
            $section = (object) ['section_type' => 'high_point', 'slug' => 'high-point'];
            $products = $productService->buildSectionProductQuery($section)->paginate(16);
        } elseif ($type === 'high_cashback') {
            $pageTitle = 'অধিক ক্যাশব্যাকের পণ্য';
            $section = (object) ['section_type' => 'high_cashback', 'slug' => 'high-cashback'];
            $products = $productService->buildSectionProductQuery($section)->paginate(16);
        } elseif ($type === 'featured') {
            $pageTitle = 'ফিচার পণ্য';
            $section = (object) ['section_type' => 'featured', 'slug' => 'featured-products'];
            $products = $productService->buildSectionProductQuery($section)->paginate(16);
        } elseif ($type === 'new') {
            $pageTitle = 'নতুন পণ্য';
            $products = Product::where('is_deleted', 0)
                ->withCount('productReviews')
                ->withAvg('productReviews', 'rating')
                ->orderByDesc('id')
                ->paginate(16);
        } else {
            $products = Product::where('is_deleted', 0)
                ->withCount('productReviews')
                ->withAvg('productReviews', 'rating')
                ->orderByDesc('id')
                ->paginate(16);
        }

        if ($request->ajax() || $request->boolean('ajax')) {
            return $this->renderInfiniteScrollJson($products);
        }

        return view('website.all_products', [
            'products' => $products,
            'pageTitle' => $pageTitle,
        ]);
    }

    protected function renderInfiniteScrollJson($products)
    {
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
}
