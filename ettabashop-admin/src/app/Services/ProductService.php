<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    function CheckSlug($slug, $ignoreId = null)
    {
        $query = Product::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        $product = $query->first();

        return $product != null ? 1 : 0;
    }

    public function GenerateSlug($productTitle, $fallbackId = null)
    {
        $title = trim((string) $productTitle);
        // Remove URL-breaking characters like /, ?, #, %, &, quotes, backslashes
        $clean = preg_replace('#[/\?\#%&\'"\\\\]+#u', '-', $title);
        $clean = preg_replace('/\s+/u', '-', $clean);
        $clean = preg_replace('/-+/u', '-', trim($clean, '-'));

        if ($clean === '') {
            $clean = 'product-' . ($fallbackId ?: time());
        }

        return $clean;
    }

    public function UpdateSlug(Product $product)
    {
        $baseTitle = !empty(trim((string) $product->name_en)) ? $product->name_en : $product->name_bn;
        $slug = $this->GenerateSlug($baseTitle, $product->id);

        if ($this->CheckSlug($slug, $product->id) != 0) {
            $slug = $slug . '-' . $product->id;
        }

        return $product->update(['slug' => $slug]);
    }

    public function UpdateUniqueID($product)
    {
        $uniqueId = '14' . $product->id;
        return $product->update(['unique_id' => $uniqueId]);
    }
}
