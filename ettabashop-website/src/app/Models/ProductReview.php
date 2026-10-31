<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'review_message',
        'rating',
    ];

    public static function ensureSchemaColumns()
    {
        try {
            if (Schema::hasTable('product_reviews')) {
                if (!Schema::hasColumn('product_reviews', 'user_id')) {
                    Schema::table('product_reviews', function ($table) {
                        $table->unsignedBigInteger('user_id')->nullable()->after('product_id');
                    });
                }
                if (!Schema::hasColumn('product_reviews', 'order_id')) {
                    Schema::table('product_reviews', function ($table) {
                        $table->unsignedBigInteger('order_id')->nullable()->after('user_id');
                    });
                }
            }
        } catch (\Throwable $e) {
            // ignore if already added
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function GetData($data)
    {
        return $data;
    }
}
