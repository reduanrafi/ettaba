{{-- resources/views/product-details.blade.php --}}
@extends('new.layouts.app')

@php
$relatedProducts = [
    (object)[
        'title' => 'OLEVS 5610 New Luxury Fashion Glass Quartz Analog Men Watch-Water Resistant',
        'price' => 19.99,
        'selling_price' => 24.99,
        'rating' => 4,
        'reviews_count' => 123,
        'image' => 'https://elitemart.com.bd/uploads/product/2024-12-10-6757c89faabc5.jpg',
    ],
    (object)[
        'title' => 'China Mesh Fabrics Exclusive T Shirt (Friday Offer)',
        'price' => 14.50,
        'selling_price' => 18.00,
        'rating' => 5,
        'reviews_count' => 89,
        'image' => 'https://elitemart.com.bd/uploads/product/2024-11-15-67375ab639b6b.jpg',
    ],
    (object)[
        'title' => 'US Polo Blue',
        'price' => 25.00,
        'selling_price' => 30.00,
        'rating' => 3,
        'reviews_count' => 45,
        'image' => 'https://elitemart.com.bd/uploads/product/2024-08-29-66d016fe1edc7.jpg',
    ],
    (object)[
        'title' => '৫ টি পলো টি-শার্ট মাত্র ১০০০ টাকা',
        'price' => 1000.00,
        'selling_price' => 1000.00,
        'rating' => 4,
        'reviews_count' => 67,
        'image' => 'https://elitemart.com.bd/uploads/product/2025-07-19-687be60d887ab.png',
    ],
    (object)[
        'title' => 'Illiyeen Panjabi - Sahara Panjabi - 267013',
        'price' => 12500.00,
        'selling_price' => 9990.00,
        'rating' => 5,
        'reviews_count' => 210,
        'image' => 'https://elitemart.com.bd/uploads/product/2025-03-16-67d6c3dbec1de.jpg',
    ],
];
@endphp

@push('styles')
<script src="https://unpkg.com/medium-zoom@1.0.6/dist/medium-zoom.min.js"></script>
@endpush

@section('content')
    <div class="max-w-[1100px] mx-auto px-4 py-8 mt-14 lg:mt-0 bg-white lg:bg-transparent" x-data="{ tab: 'description', quantity: 1 }">
            {{-- Left Side: Split into two columns --}}
        <div class="grid grid-cols-12 items-start bg-white gap-5 p-3">
            {{-- LEFT: Sticky Product Image --}}
            <div class="col-span-12 lg:col-span-4">
                <div class="sticky top-10">
                    <div class="p-3 border border-gray-200 rounded bg-white">
                        <img src="https://elitemart.com.bd/uploads/product/2025-05-05-6818ac92304a5.png" alt="Product Image" class="rounded w-full h-auto zoomable" >
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-5">
                <h1 class="text-xl">BURBERRY Premium polo shirt (Sky)</h1>
                <div class="mt-5">
                    @for ($i = 0; $i < 5; $i++)
                        <i class="fa-regular fa-star text-xs text-yellow-500"></i>
                    @endfor
                    <span class="text-xs">
                        0 Rating of 25 orders
                    </span>
                </div>
                <div class="my-2">
                    <div class="my-1">
                        Seller: <a href="#" class="text-blue-500">Elite Lifestyle</a>
                    </div>
                    <div class="my-1">
                        Brand: <a href="#" class="text-blue-500 text-sm">Burberry</a>
                    </div>
                </div>
                <div class="my-3 font-bold text-xl">
                    Tk. 350.00
                </div>
                <div class="flex gap-1">
                    <i class="fa-regular fa-circle-check text-green-500 text-xl"></i>
                    <div>
                        <div class="text-xs">
                            In Stock (199 copies available) <br />
                            * স্টক আউট হওয়ার আগেই অর্ডার করুন
                        </div>
                    </div>
                </div>

                <div class="my-4">
                    <p class="text-xl text-gray-500 mb-1">Select Size</p>

                    <div class="flex space-x-3">
                        @php
                        $sizes = ['M', 'L', 'XL'];
                        $selectedSize = ''; // default selected, adjust as needed
                        @endphp
                        
                        @foreach ($sizes as $size)
                        <label class="cursor-pointer flex flex-col items-center">
                            <input
                            type="radio"
                            name="size"
                            value="{{ $size }}"
                            class="hidden peer"
                            @if ($size === $selectedSize) checked @endif
                            />
                            <div
                            class="size-7 border rounded-md flex items-center justify-center text-gray-700
                                    peer-checked:border-[#007025] peer-checked:bg-[#6ac08791] peer-checked:text-[#007025]
                                    hover:border-[#007025] transition"
                            >
                            {{ $size }}
                            </div>
                            <!-- <span class="mt-2 text-sm text-gray-600 select-none">{{ $size }}</span> -->
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <div x-data="{ quantity: 1 }" class="inline-flex items-center overflow-hidden w-36 space-x-2 ">
                        <button
                            @click="if(quantity > 1) quantity--"
                            class="px-2 py-1 border rounded cursor-pointer transition text-lg font-bold"
                            type="button"
                            aria-label="Decrease quantity"
                        ><i class="fa-solid fa-minus text-sm"></i></button>

                        <input
                            type="number"
                            x-model.number="quantity"
                            min="1"
                            class="w-full text-center rounded bg-gray-200 p-2 outline-none"
                            aria-label="Quantity input"
                        />

                        <button
                            @click="quantity++"
                            class="px-2 py-1 border cursor-pointer rounded transition text-lg font-bold"
                            type="button"
                            aria-label="Increase quantity"
                        ><i class="fa-solid fa-plus text-sm"></i></button>
                    </div>
                    <span>(199 available)</span>
                </div>

                <div class="space-x-5 flex my-3">
                    <div>
                        <button class="px-3 py-2.5 text-nowrap text-white rounded cursor-pointer bg-[#ff9900] hover:bg-[#ffc369]"><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                    </div>
                    <div>
                        <button class="px-3 text-nowrap py-2.5 text-white rounded cursor-pointer bg-[#007025] hover:bg-[#3e7c53]"><i class="fa-solid fa-bolt"></i> Buy Now</button>
                    </div>

                </div>

                <div class="my-10 grid-cols-2 grid text-xs gap-3">
                    <div class="">
                        <a href="#">
                            <i class="fa-regular fa-heart"></i>
                            <span>Add to Whitelist</span>
                        </a>
                    </div>
                    
                    <div x-data="{ open: false }" class="relative inline-block">
                        <button 
                            @click="open = !open" 
                            class="cursor-pointer"
                            type="button"
                        >
                            <i class="fa-solid fa-share"></i>
                            <span>Share This Item</span>
                            <i class="fa-solid fa-caret-down"></i>
                        </button>

                        <!-- Dropdown -->
                        <div 
                            x-show="open" 
                            @click.outside="open = false" 
                            x-transition
                            class="absolute mt-2 right-0 bg-white p-3 rounded shadow-lg z-50"
                            style="display: none;"
                        >
                            <div class="flex space-x-1 items-center">
                                <span class="text-lg text-base">
                                    Share: 
                                </span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('new.product.show', 1000) }}" class="bg-[#007030] p-0.5 text-white rounded"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?url={{ route('new.product.show', 1000) }}&text=" class="bg-[#007030] p-0.5 text-white rounded">
                                    <i class="fa-brands fa-x-twitter"></i>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('new.product.show', 1000) }}" class="bg-[#007030] p-0.5 text-white rounded">
                                    <i class="fa-brands fa-linkedin-in"></i>
                                </a>
                                <a href="https://www.addtoany.com/add_to/email?linkurl={{ route('new.product.show', 1000) }}&linknote=" class="bg-[#007030] p-0.5 text-white rounded">
                                    <i class="fa-solid fa-envelope"></i>
                                </a>
                                <a href="https://www.addtoany.com/add_to/whatsapp?linkurl={{ route('new.product.show', 1000) }}&linknote=" class="bg-[#007030] p-0.5 text-white rounded">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                                <a href="https://www.addtoany.com/add_to/facebook_messenger?linkurl={{ route('new.product.show', 1000) }}&linknote=" class="bg-[#007030] p-0.5 text-white rounded">
                                    <i class="fa-brands fa-facebook-messenger"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span> 2 days happy return</span>
                    </div>
                    <div class="">
                        <i class="fa-solid fa-recycle"></i>
                        <span> 5 days of warranty</span>
                    </div>
                    <div class="">
                        <i class="fa-solid fa-truck-moving"></i>
                        <span> Home delivery <span class="text-[10px]">est. 2/3 days</span>
                            </span>
                    </div>
                    <div class="flex gap-1.5 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0" viewBox="0 0 16.933 16.933" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M6.071 1.907c-.208.312.288.601.457.266A2.246 2.246 0 0 1 9.05 1.14c.82.22 1.43.877 1.612 1.677l-.497-.296c-.316-.16-.565.264-.27.457l.885.522c.173.128.35.094.457-.093l.594-.848c.196-.311-.235-.58-.434-.303l-.244.347A2.775 2.775 0 0 0 9.188.628c-1.396-.374-2.602.376-3.117 1.28zm-.96 2.148c-.227.294.237.617.434.303l.237-.339A2.78 2.78 0 0 0 7.75 5.995a2.761 2.761 0 0 0 3.116-1.282c.21-.313-.29-.602-.457-.264a2.247 2.247 0 0 1-4.134-.642l.489.289c.308.196.59-.28.27-.456l-.905-.534a.275.275 0 0 0-.412.083zM.687 6.512c-.067.15.024.31.161.357a1.713 1.713 0 1 0 3.323.012h.857c.352 0 .35-.53 0-.53h-.775a1.997 1.997 0 0 0-1.776-1.045c-.763 0-1.48.476-1.79 1.206zm13.74-.942a1.713 1.713 0 1 0 0 3.426 1.713 1.713 0 0 0 0-3.426zm-8.87 2.367a.798.798 0 0 0-.795.792v2.117a.8.8 0 0 0 .795.796h2.646c.435 0 .792-.36.792-.796V8.73a.796.796 0 0 0-.792-.792h-.529v1.321c0 .174-.162.3-.33.258L6.88 9.4l-.464.116a.264.264 0 0 1-.329-.258V7.937zm1.059 0v.984l.2-.051a.262.262 0 0 1 .128 0l.201.051v-.984zm4.06.267v.154a.977.977 0 0 0-.703.941c0 .301.133.513.365.631.2.102.4.113.575.131.175.018.326.042.39.074.06.033.07.066.074.162 0 .23-.171.425-.395.447-.263.01-.412-.14-.47-.355-.069-.346-.589-.243-.52.103a.976.976 0 0 0 .684.746v.14c0 .352.53.352.53 0v-.14a.973.973 0 0 0 .7-.94c0-.325-.15-.526-.363-.633-.2-.102-.4-.113-.576-.131-.235-.024-.325-.042-.389-.075-.028-.014-.076-.035-.076-.16 0-.255.202-.449.437-.449.24 0 .4.188.428.356.07.346.59.242.52-.104a.98.98 0 0 0-.682-.744v-.154c0-.355-.53-.355-.53 0zM.529 11.178v4.963c0 .146.12.264.266.263.97-.005 2.786 0 2.91 0a.264.264 0 0 0 .264-.263v-2.126a.53.53 0 0 1-.144-.068c-.557-.375-1.399-.932-1.83-1.228a1.25 1.25 0 0 1-.46-1.7c.342-.589 1.187-.846 1.758-.418l.672.505a1.723 1.723 0 0 0-1.727-1.65c-.936 0-1.709.774-1.709 1.722zm12.44-.072.671-.504c.645-.406 1.442-.147 1.758.418a1.258 1.258 0 0 1-.426 1.679l-1.863 1.249a.531.531 0 0 1-.144.067s-.003 1.417 0 2.126c0 .145.118.263.263.263h2.91a.265.265 0 0 0 .266-.263v-4.963c0-.948-.772-1.721-1.709-1.721-1.076 0-1.716.9-1.727 1.649zm-10.976.18a.707.707 0 0 0 .263.973l1.862 1.248h1.869c.318 0 .575-.254.575-.57s-.257-.587-.575-.57H4.764l-1.789-1.342a.72.72 0 0 0-.982.261zm11.965-.26-1.788 1.342h-1.223c-.318-.018-.576.254-.576.57 0 .315.257.57.576.57h1.868l1.862-1.249a.707.707 0 0 0 .263-.973.723.723 0 0 0-.982-.26z" fill="#000000" opacity="1" data-original="#000000"></path></g></svg>
                        <span>
                            Cash on Delivery
                        </span>
                    </div>
                </div>
            </div>

            <div class="hidden lg:block lg:col-span-3 bg-[#f3f3f3] -mt-4 -mr-4 -mb-4 p-3">
                <h2 class="text-xl">Related Products</h2>
                <div class="">
                    @foreach ($relatedProducts as $product)
                    <div class="grid grid-cols-12 p-3 gap-3 border-b border-b-[#e6e4e4]">
                        <!-- Image -->
                        <div class=" flex-shrink-0 col-span-4">
                            <img src="{{ $product->image }}" alt="{{ $product->title }}" class="w-full h-[85px] object-cover rounded">
                        </div>

                        <!-- Info -->
                        <div class="flex flex-col justify-between flex-1 col-span-7">
                            <div>
                                <a href="{{ route('new.product.show', 9876732) }}" class="text-gray-800 leading-[14px]">{{ $product->title }}</a>
                                <div class="flex items-center gap-2 mt-1 text-sm">
                                    <span class="text-red-600 font-bold text-base">Tk.{{ number_format($product->price, 2) }}</span>
                                    <span class="line-through text-gray-500">Tk.{{ number_format($product->selling_price, 2) }}</span>
                                </div>
                            </div>
                            <div class="mt-2 flex items-center text-yellow-400 text-xs">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $product->rating)
                                        <i class="fas fa-star text-[10px]"></i>
                                    @else
                                        <i class="far fa-star text-[10px]"></i>
                                    @endif
                                @endfor
                                <span class="ml-2 text-gray-600 text-sm text-nowrap">({{ $product->reviews_count }} reviews)</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-10 bg-white rounded shadow-sm p-3">
            <h2 class="my-3 text-xl font-semibold">Product Specifications & Summary</h2>
            <div class="border-b border-gray-300 mb-6 -ml-3 pl-3 overflow-x-auto">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="tab = 'description'"
                        :class="tab === 'description' ? 'border-t-[#007020] border-t-3 border-x border-b-inherit border-x-gray-300 p-2 text-[#007020] font-semibold pt-2' : 'border-transparent cursor-pointer text-gray-600 hover:border-gray-300 border-b-2 pb-2'" class="text-nowrap"
                        type="button">Summary</button>

                    <button @click="tab = 'reviews'"
                        :class="tab === 'reviews' ? 'border-t-[#007020] border-t-3 border-x border-b-inherit border-x-gray-300 p-2 text-[#007020] font-semibold pt-2' : 'border-transparent cursor-pointer text-gray-600 hover:border-gray-300 border-b-2 pb-2'" class="text-nowrap"
                        type="button">Reviews (0)</button>

                    <button @click="tab = 'questions'"
                        :class="tab === 'questions' ? 'border-t-[#007020] border-t-3 border-x border-b-inherit border-x-gray-300 p-2 text-[#007020] font-semibold pt-2' : 'border-transparent cursor-pointer text-gray-600 hover:border-gray-300 border-b-2 pb-2'" class="text-nowrap"
                        type="button">Ask Question</button>
                </nav>
            </div>
            

            {{-- Tab Contents --}}
            <div class="pl-2">
                <div x-show="tab === 'description'" x-cloak class="transition-all duration-500">
                    <ul class="list-disc pl-6">
                        <li>Shop Name: Basic Wear                        </li>
                        <li>    Brand: Burberry
                      </li>
                        <li>    Product Type: Premium Quality POLO.
                     </li>
                        <li>    Fabric: 100% Cotton PK Imported.
                     </li>
                        <li>    GSM: 200
                      </li>
                        <li>    
                            Available Sizes:  | M | L | XL | (M-38 | L-40 | XL-42)
                      </li>
                        <li>    
                            Measurement: Asian Standard Fitting.
                      </li>
                        <li>    
                            Color: Red and Sky Blue (Same as picture).
                      </li>
                    </ul>
                </div>

                <div x-show="tab === 'reviews'" x-cloak>
                    <p>No reviews yet. Be the first to review this product!</p>
                </div>

                <div x-show="tab === 'questions'" x-cloak>
                    <p>Have a question? Ask us below.</p>
                    <form class="mt-4 max-w-lg">
                        <textarea class="w-full border rounded p-2" rows="4"
                            placeholder="Write your question here..."></textarea>
                        <button type="submit"
                            class="mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Row 3: Similar Products --}}
        <!-- <div class="mt-12 bg-white p-3">
            <h2 class="text-2xl font-semibold mb-6">Similar Products</h2>
            @php
                $similarProducts = $relatedProducts;
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($similarProducts as $sp)
                    <div class="border rounded p-4 hover:shadow-lg transition">
                        <img src="{{ $sp->image }}" alt="{{ $sp->title }}" class="w-full h-40 object-cover mb-2 rounded" />
                        <h3 class="font-semibold">{{ $sp->title }}</h3>
                        <p class="text-green-600">${{ $sp->selling_price }}</p>
                    </div>
                @endforeach
            </div>
        </div> -->

        <div class="featured-glide mt-4">
        <div class="glide__track" data-glide-el="track">
            <ul class="glide__slides">
                @foreach ($similarProducts as $product)
                    <li class="glide__slide">
                        <div class="rounded-lg bg-white shadow-sm flex flex-col justify-between h-[366px] min-w-0 
                        transition-all duration-300 transform hover:scale-[1.02] hover:shadow-md">

                            {{-- Product Image --}}
                            <div class="overflow-hidden rounded-t-lg">
                                <img src="{{ $product->image }}" onerror="this.src='https://via.placeholder.com/150'"
                                    alt="{{ $product->title }}"
                                    class="w-full h-52 object-cover transition-transform duration-300 hover:scale-105" />
                            </div>

                            {{-- Product Details --}}
                            <div class="p-3 flex-1 flex flex-col justify-between">
                                {{-- Rating --}}
                                @php
                                    $rating = random_int(0,5);
                                @endphp
                                <div class="text-yellow-400 text-sm mb-1">
                                    @for ($i = 0; $i < $rating; $i++)
                                        ★
                                    @endfor
                                    @for ($i = $rating; $i < 5; $i++)
                                        <span class="text-gray-300">★</span>
                                    @endfor
                                </div>

                                {{-- Price --}}
                                <div class="text-sm mb-1">
                                    <span
                                        class="text-red-600 font-semibold">${{ number_format($product->selling_price, 2) }}</span>
                                    <span
                                        class="line-through text-gray-400 ml-1 text-xs">${{ number_format($product->price, 2) }}</span>
                                </div>

                                {{-- Product Name --}}
                                <a href="{{ route('new.product.show', 9876732) }}" class="text-gray-800 text-sm font-semibold text-[#007025] hover:underline">
                                    {{ $product->title }}
                                </a>
                            </div>

                            {{-- CTA Buttons --}}
                            <div class="flex gap-5 mt-2 p-3">
                                <button class="inline-flex cursor-pointer bg-gray-200 text-white text-xs px-2 py-1 rounded 
                                   transition-all duration-200 hover:bg-gray-100 justify-center items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="25" height="25" x="0" y="0" viewBox="0 0 450.391 450.391" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M143.673 350.322c-25.969 0-47.02 21.052-47.02 47.02 0 25.969 21.052 47.02 47.02 47.02 25.969 0 47.02-21.052 47.02-47.02.001-25.968-21.051-47.02-47.02-47.02zm0 73.143c-14.427 0-26.122-11.695-26.122-26.122s11.695-26.122 26.122-26.122 26.122 11.695 26.122 26.122c.001 14.427-11.695 26.122-26.122 26.122zM342.204 350.322c-25.969 0-47.02 21.052-47.02 47.02 0 25.969 21.052 47.02 47.02 47.02s47.02-21.052 47.02-47.02c0-25.968-21.051-47.02-47.02-47.02zm0 73.143c-14.427 0-26.122-11.695-26.122-26.122s11.695-26.122 26.122-26.122 26.122 11.695 26.122 26.122c.001 14.427-11.695 26.122-26.122 26.122zM448.261 76.037a13.064 13.064 0 0 0-8.359-4.18L99.788 67.155 90.384 38.42C83.759 19.211 65.771 6.243 45.453 6.028H10.449C4.678 6.028 0 10.706 0 16.477s4.678 10.449 10.449 10.449h35.004a27.17 27.17 0 0 1 25.078 18.286l66.351 200.098-5.224 12.016a50.154 50.154 0 0 0 4.702 45.453 48.588 48.588 0 0 0 39.184 21.943h203.233c5.771 0 10.449-4.678 10.449-10.449s-4.678-10.449-10.449-10.449H175.543a26.646 26.646 0 0 1-21.943-12.539 28.733 28.733 0 0 1-2.612-25.078l4.18-9.404 219.951-22.988c24.16-2.661 44.034-20.233 49.633-43.886L449.83 84.917a8.882 8.882 0 0 0-1.569-8.88zm-43.885 109.191c-3.392 15.226-16.319 26.457-31.869 27.69l-217.339 22.465-48.588-147.33 320.261 4.702-22.465 92.473z" fill="#007025" opacity="1" data-original="#000000" class=""></path></g></svg>
                                </button>
                                <button class="flex-1 cursor-pointer bg-green-600 text-white text-xs px-2 py-1 rounded 
                                   transition-all duration-200 hover:bg-green-700">
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    </div>
@endsection


@push('scripts')
<script>
    new Glide('.featured-glide', {
        type: 'carousel',
        startAt: 0,
        perView: 6,
        gap: 10,
        autoplay: 1500,
        hoverpause: true,
        rewind: true,
        animationDuration: 600,
        breakpoints: {
            1280: { perView: 5 },
            1024: { perView: 4 },
            768: { perView: 2 },
            480: { perView: 2 }
        }
    }).mount();

</script>
@endpush