<div class="product-tile h-100 shadow-sm border-0 rounded-lg overflow-hidden animate-fade-in bg-white">
    <div class="product-tile-image position-relative">
        <a href="{{ route('product.detail', ['slug' => $product->slug]) }}" class="d-block overflow-hidden">
            <img src="{{ env('IMAGE_URL') . $product->featured_image }}" class="img-fluid w-100 transition-transform"
                alt="{{ $product->name_en }}">
        </a>
        @if($product->tcb_en > 0)
            <div class="position-absolute" style="top: 10px; left: 10px; z-index: 2;">
                <span class="btn btn-sm btn-primary shadow-sm" style="display: inline-block;
                            background: #2ecc71;
                            color: white;
                            padding: 4px 10px;
                            font: bold 11px sans-serif;
                            border-radius: 0 50px 50px 0; /* Only right side rounded */
                            margin-bottom: 8px;
                            box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                    <i class="fas fa-coins mr-1"></i>{{$product->tcb_en}}৳ {{__('titles.cashBack')}}
                </span>
            </div>
        @endif
    </div>

    <div class="product-tile-info p-3 d-flex flex-column">
        <h6 class="product-name mb-2">
            <a href="{{ route('product.detail', ['slug' => $product->slug]) }}" class="text-dark text-decoration-none">
                {{ $local == 'bn' ? $product->name_en : $product->name_en }}
            </a>
        </h6>

        <div class="product-price-container my-2">
            <div class="d-flex align-items-baseline flex-wrap">
                <div class="mr-3">
                    <span class="price-label">ERP:</span>
                    <span class="price-main ml-1">{{ $product->erp_en }}৳</span>
                </div>
                @if($product->mrp_en > $product->erp_en)
                    <div>
                        <span class="price-label">MRP:</span>
                        <span class="price-mrp ml-1">{{ $product->mrp_en }}৳</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="product-footer mt-auto">
            <button class="btn btn-primary btn-block btn-modern-v2 rounded-pill font-weight-bold py-2 px-3"
                @click="addToCart('{{$product->id}}', '{{$product->name_en}}', '{{$product->erp_en}}', '{{$product->trp_en}}', '{{$product->owner_id}}', '{{$product->tcb_en}}', '{{$product->rate_en}}')">
                <i class="fas fa-shopping-basket mr-2"></i>{{__("buttons.cart")}}
            </button>
        </div>
    </div>
</div>

<style>
    .product-tile {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        border: 1px solid transparent !important;
    }

    .product-tile:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12) !important;
        border-color: rgba(99, 102, 241, 0.2) !important;
    }

    .product-tile-image {
        aspect-ratio: 1/1;
        background: #f8fafc;
        overflow: hidden;
    }

    .product-tile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .transition-transform {
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .product-tile:hover .transition-transform {
        transform: scale(1.1);
    }

    .product-name {
        font-size: 0.95rem !important;
        font-weight: 700 !important;
        min-height: 2.8rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }

    .price-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        color: #94a3b8;
        letter-spacing: 0.5px;
        font-weight: 700;
    }

    .price-main {
        font-size: 1.25rem !important;
        color: var(--primary);
        font-weight: 800;
        line-height: 1;
    }

    .price-mrp {
        font-size: 0.85rem !important;
        text-decoration: line-through;
        color: #cbd5e1;
        font-weight: 600;
    }

    .badge-cashback-solid {
        background: #10b981;
        color: white;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        letter-spacing: 0.5px;
    }

    .btn-modern-v2 {
        background: #6366f1 !important;
        color: white !important;
        border: none !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4) !important;
        transition: all 0.3s ease !important;
    }

    .btn-modern-v2:hover {
        background: #4f46e5 !important;
        transform: scale(1.05) !important;
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6) !important;
    }

    .product-tile:hover .product-name a {
        color: #6366f1 !important;
    }

    @media (max-width: 576px) {
        .product-tile-info {
            padding: 0.75rem !important;
        }

        .product-name {
            font-size: 0.85rem !important;
            min-height: 2.4rem;
        }

        .price-main {
            font-size: 1.1rem !important;
        }

        .badge-cashback-solid {
            padding: 3px 8px;
            font-size: 0.65rem;
        }
    }
</style>