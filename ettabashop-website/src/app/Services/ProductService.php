<?php

namespace App\Services;

use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductService
{
    public function GetHomeProducts()
    {
        try {
            $products = DB::select(DB::raw('
                SELECT * FROM top_products tp
                JOIN products p on p.id = tp.product_id
            '));
            return $this->MakeHomeProducts($products);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function MakeHomeProducts($homeProducts)
    {
        $products = [];
        foreach ($homeProducts as $k => $product) {
            if ($product->type_id == 1) {
                $products['new'][$k] = $product;
            } elseif ($product->type_id == 2) {
                $products['featured'][$k] = $product;
            } elseif ($product->type_id == 3) {
                $products['topRated'][$k] = $product;
            } elseif ($product->type_id == 4) {
                $products[$k]['mostDiscounted'] = $product;
            } elseif ($product->type_id == 5) {
                $products[$k]['offers'] = $product;
            } elseif ($product->type_id == 6) {
                $products[$k]['discountProduct'] = $product;
            } elseif ($product->type_id == 7) {
                $products['mostPoint'][$k] = $product;
            } elseif ($product->type_id == 8) {
                $products['dailyNeeds'][$k] = $product;
            } elseif ($product->type_id == 9) {
                $products['fashion'][$k] = $product;
            } elseif ($product->type_id == 10) {
                $products['library'][$k] = $product;
            } elseif ($product->type_id == 11) {
                $products['jewelry'][$k] = $product;
            } elseif ($product->type_id == 12) {
                $products['electronicGoods'][$k] = $product;
            } elseif ($product->type_id == 13) {
                $products['electronicAccessories'][$k] = $product;
            }
        }
        return $products;
    }

    public function getDefaultHomeSections()
    {
        $defaults = [
            [
                'id' => 1,
                'title_bn' => 'লোকাল পণ্য',
                'title_en' => 'Local Products',
                'slug' => 'local-products',
                'section_type' => 'local_products',
                'category_id' => null,
                'icon_class' => 'fas fa-store-alt',
                'icon_color' => '#15803d',
                'product_limit' => 4,
                'sort_order' => 1,
                'is_visible' => 1,
            ],
            [
                'id' => 2,
                'title_bn' => 'অধিক ক্যাশব্যাকের পণ্য',
                'title_en' => 'High Cashback Products',
                'slug' => 'high-cashback',
                'section_type' => 'high_cashback',
                'category_id' => null,
                'icon_class' => 'fas fa-percentage',
                'icon_color' => '#15803d',
                'product_limit' => 4,
                'sort_order' => 2,
                'is_visible' => 1,
            ],
            [
                'id' => 3,
                'title_bn' => 'অধিক পয়েন্টের পণ্য',
                'title_en' => 'High Point Products',
                'slug' => 'high-point',
                'section_type' => 'high_point',
                'category_id' => null,
                'icon_class' => 'fas fa-coins',
                'icon_color' => '#f59e0b',
                'product_limit' => 4,
                'sort_order' => 3,
                'is_visible' => 1,
            ],
            [
                'id' => 4,
                'title_bn' => 'ফিচার পণ্য',
                'title_en' => 'Featured Products',
                'slug' => 'featured-products',
                'section_type' => 'featured',
                'category_id' => null,
                'icon_class' => 'fas fa-bolt',
                'icon_color' => '#eab308',
                'product_limit' => 4,
                'sort_order' => 4,
                'is_visible' => 1,
            ],
            [
                'id' => 5,
                'title_bn' => 'অর্গানিক পণ্য',
                'title_en' => 'Organic Products',
                'slug' => 'organic-products',
                'section_type' => 'category',
                'category_id' => null,
                'icon_class' => 'fas fa-leaf',
                'icon_color' => '#16a34a',
                'product_limit' => 4,
                'sort_order' => 5,
                'is_visible' => 1,
            ],
            [
                'id' => 6,
                'title_bn' => 'ফ্যাশন ও লাইফস্টাইল',
                'title_en' => 'Fashion & Lifestyle',
                'slug' => 'fashion-lifestyle',
                'section_type' => 'category',
                'category_id' => null,
                'icon_class' => 'fas fa-tshirt',
                'icon_color' => '#2563eb',
                'product_limit' => 4,
                'sort_order' => 6,
                'is_visible' => 1,
            ],
            [
                'id' => 7,
                'title_bn' => 'ইলেকট্রনিক্স পণ্য',
                'title_en' => 'Electronics Products',
                'slug' => 'electronics-products',
                'section_type' => 'category',
                'category_id' => null,
                'icon_class' => 'fas fa-tv',
                'icon_color' => '#0284c7',
                'product_limit' => 4,
                'sort_order' => 7,
                'is_visible' => 1,
            ],
            [
                'id' => 8,
                'title_bn' => 'সকল পণ্য',
                'title_en' => 'All Products',
                'slug' => 'all-products',
                'section_type' => 'all_products',
                'category_id' => null,
                'icon_class' => 'fas fa-th-large',
                'icon_color' => '#dc2626',
                'product_limit' => 10,
                'sort_order' => 8,
                'is_visible' => 1,
            ],
        ];

        return collect($defaults)->map(function ($item) {
            return (object) $item;
        });
    }

    public function getActiveHomeSections()
    {
        try {
            if (Schema::hasTable('home_sections')) {
                $sections = HomeSection::where('is_visible', 1)
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();
                if ($sections->isNotEmpty()) {
                    return $sections;
                }
            }
        } catch (\Throwable $e) {
            // Fallback to default sections
        }

        return $this->getDefaultHomeSections();
    }

    public function findSectionBySlug($slug)
    {
        try {
            if (Schema::hasTable('home_sections')) {
                $section = HomeSection::where('slug', $slug)->first();
                if ($section) {
                    return $section;
                }
            }
        } catch (\Throwable $e) {
        }

        return $this->getDefaultHomeSections()->firstWhere('slug', $slug);
    }

    public function canLoadProductReviews()
    {
        try {
            return Schema::hasTable('product_reviews')
                && Schema::hasColumn('product_reviews', 'product_id')
                && Schema::hasColumn('product_reviews', 'rating');
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function buildSectionProductQuery($section)
    {
        $query = Product::where('is_deleted', 0);
        if ($this->canLoadProductReviews()) {
            $query->withCount('productReviews')
                  ->withAvg('productReviews', 'rating');
        }

        $type = $section->section_type ?? 'all_products';
        $slug = $section->slug ?? '';

        if ($type === 'high_cashback' || $type === 'auto_cashback' || $slug === 'high-cashback') {
            return $query->orderByRaw('CAST(REPLACE(COALESCE(tcb_en, "0"), ",", "") AS DECIMAL(10,2)) DESC, COALESCE(sold_amount, 0) DESC, id DESC');
        }

        if ($type === 'high_point' || $type === 'auto_point' || $slug === 'high-point') {
            return $query->orderByRaw('CAST(REPLACE(COALESCE(trp_en, "0"), ",", "") AS DECIMAL(10,2)) DESC, COALESCE(sold_amount, 0) DESC, id DESC');
        }

        if ($type === 'featured' || $slug === 'featured-products') {
            $featuredCount = (clone $query)->where('is_featured', 1)->count();
            if ($featuredCount > 0) {
                return $query->where('is_featured', 1)->orderByDesc('id');
            }
            return $query->orderByDesc('sold_amount')->orderByDesc('id');
        }

        // Gather category IDs from either category_ids (JSON array) or category_id
        $sectionCatIds = [];
        if (isset($section->category_ids_array) && is_array($section->category_ids_array) && !empty($section->category_ids_array)) {
            foreach ($section->category_ids_array as $cid) {
                $sectionCatIds = array_merge($sectionCatIds, $this->getCategoryAndChildrenIds($cid));
            }
        } elseif (!empty($section->category_ids)) {
            $decoded = is_array($section->category_ids) ? $section->category_ids : json_decode($section->category_ids, true);
            if (is_array($decoded)) {
                foreach ($decoded as $cid) {
                    $sectionCatIds = array_merge($sectionCatIds, $this->getCategoryAndChildrenIds($cid));
                }
            }
        } elseif (!empty($section->category_id)) {
            $sectionCatIds = $this->getCategoryAndChildrenIds($section->category_id);
        }
        $sectionCatIds = array_values(array_unique(array_filter($sectionCatIds)));

        if ($type === 'local_products' || $slug === 'local-products') {
            if (!empty($sectionCatIds)) {
                $catCount = (clone $query)->whereIn('category_id', $sectionCatIds)->count();
                if ($catCount > 0) {
                    return $query->whereIn('category_id', $sectionCatIds)->orderByDesc('id');
                }
            }
            return $query->orderByDesc('sold_amount')->orderByDesc('id');
        }

        if ($type === 'category' || !empty($sectionCatIds)) {
            $catIds = $sectionCatIds;
            if (empty($catIds)) {
                $keywords = [];
                if (str_contains($slug, 'organic')) {
                    $keywords = ['organic', 'daily', 'grocery', 'food', 'মুদি', 'অর্গানিক', 'নিত্যপ্রয়োজনীয়'];
                } elseif (str_contains($slug, 'fashion')) {
                    $keywords = ['fashion', 'cloth', 'lifestyle', 'ফ্যাশন', 'লাইফস্টাইল'];
                } elseif (str_contains($slug, 'electronic')) {
                    $keywords = ['electronic', 'gadget', 'mobile', 'computer', 'ইলেকট্রনিক্স', 'গ্যাজেট'];
                }

                if (!empty($keywords)) {
                    $matchedCats = Category::where('is_deleted', 0)
                        ->where(function ($q) use ($keywords) {
                            foreach ($keywords as $kw) {
                                $q->orWhere('name_en', 'LIKE', "%{$kw}%")
                                  ->orWhere('name_bn', 'LIKE', "%{$kw}%")
                                  ->orWhere('slug', 'LIKE', "%{$kw}%");
                            }
                        })
                        ->pluck('id')
                        ->toArray();

                    foreach ($matchedCats as $cid) {
                        $catIds = array_merge($catIds, $this->getCategoryAndChildrenIds($cid));
                    }
                    $catIds = array_unique($catIds);
                }
            }

            if (!empty($catIds)) {
                $catProductCount = (clone $query)->whereIn('category_id', $catIds)->count();
                if ($catProductCount > 0) {
                    return $query->whereIn('category_id', $catIds)->orderByDesc('id');
                }
            }

            return $query->orderByDesc('id');
        }

        return $query->orderByDesc('id');
    }

    public function getCategoryAndChildrenIds($categoryId)
    {
        try {
            $ids = [(int) $categoryId];
            $children = Category::where('parent_id', $categoryId)->where('is_deleted', 0)->pluck('id')->toArray();
            foreach ($children as $childId) {
                $ids[] = (int) $childId;
                $subChildren = Category::where('parent_id', $childId)->where('is_deleted', 0)->pluck('id')->toArray();
                foreach ($subChildren as $subId) {
                    $ids[] = (int) $subId;
                }
            }
            return array_unique($ids);
        } catch (\Throwable $e) {
            return [(int) $categoryId];
        }
    }

    public function getHomeSectionsWithProducts()
    {
        $sections = $this->getActiveHomeSections();
        $result = [];

        foreach ($sections as $section) {
            try {
                $limit = max(1, (int) ($section->product_limit ?? 4));
                $products = $this->buildSectionProductQuery($section)->take($limit)->get();
                $result[] = [
                    'section' => $section,
                    'products' => $products,
                ];
            } catch (\Throwable $e) {
                $result[] = [
                    'section' => $section,
                    'products' => collect([]),
                ];
            }
        }

        return $result;
    }
}
