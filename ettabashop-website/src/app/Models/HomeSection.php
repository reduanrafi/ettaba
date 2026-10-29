<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_bn',
        'title_en',
        'slug',
        'icon',
        'icon_color',
        'section_type',
        'category_ids',
        'product_limit',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'product_limit' => 'integer',
        'sort_order' => 'integer',
        'is_visible' => 'boolean',
    ];

    public function getCategoryIdsArrayAttribute()
    {
        if (empty($this->category_ids)) {
            return [];
        }
        if (is_array($this->category_ids)) {
            return array_map('intval', $this->category_ids);
        }
        $decoded = json_decode($this->category_ids, true);
        return is_array($decoded) ? array_map('intval', $decoded) : [];
    }
}

