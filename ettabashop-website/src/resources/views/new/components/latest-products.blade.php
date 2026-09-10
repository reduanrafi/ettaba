@php
    $products = [
        [
            'name' => 'Bluetooth Earbuds',
            'image' => 'https://img.freepik.com/premium-photo/stylish-earbuds-modern-sound-experience_1099965-79003.jpg?w=1800',
            'price' => 39.99,
            'old_price' => 59.99,
            'rating' => 4,
        ],
        [
            'name' => 'Men’s Leather Wallet',
            'image' => 'https://img.freepik.com/premium-photo/christmas-interior-with-gift-boxes-christmas-fires_73989-5622.jpg?w=1800',
            'price' => 24.50,
            'old_price' => 34.99,
            'rating' => 5,
        ],
        [
            'name' => 'Organic Face Wash',
            'image' => 'https://img.freepik.com/free-photo/beauty-portrait-pleased-dark-haired-woman-with-clean-healthy-skin-holding-lotion-removing-makeup-looking-aside-isolated-white_171337-834.jpg?t=st=1751484259~exp=1751487859~hmac=66751f2a68cd747bd8e049774ea7c72172e376980b7c7221a1a4862cc30a6daf&w=1800',
            'price' => 12.99,
            'old_price' => 18.99,
            'rating' => 3,
        ],
        [
            'name' => 'Digital Wrist Watch',
            'image' => 'https://img.freepik.com/free-vector/realistic-fitness-trackers_23-2148530529.jpg?t=st=1751484309~exp=1751487909~hmac=05d45e49776b2743f07b7d41bdbb3da4fd61a29f8d397587d78b386a0dabb485&w=1800',
            'price' => 69.00,
            'old_price' => 99.00,
            'rating' => 4,
        ],
        [
            'name' => 'Scented Candle Set',
            'image' => 'https://img.freepik.com/premium-photo/burning-candle-long-white-ceramic-glass-vase-brown-clay-jug-with-two-dry-wildflowers-standing-linen-tablecloth-black-wall_274679-10761.jpg?w=1800',
            'price' => 16.75,
            'old_price' => 25.00,
            'rating' => 4,
        ],
        [
            'name' => 'Aloe Vera Gel',
            'image' => 'https://img.freepik.com/free-photo/aloe-vera-cosmetic-cream-white-surface_1150-42275.jpg?t=st=1751483282~exp=1751486882~hmac=2bb24e7853e2bd8aa5cfcbae5dcdb98aa0a67ffda77de10d2b3980508f67e146&w=1800',
            'price' => 10.99,
            'old_price' => 14.99,
            'rating' => 5,
        ],
        [
            'name' => 'Casual Sneakers',
            'image' => 'https://img.freepik.com/free-photo/brown-leather-shoes_1203-7562.jpg?t=st=1751484415~exp=1751488015~hmac=4114dd4424a06a20e3d6646afabb10241f2fea7a0d14b0a30d1b6dcdcdf0c36e&w=1800',
            'price' => 45.00,
            'old_price' => 65.00,
            'rating' => 4,
        ],
        [
            'name' => 'Leather Belt',
            'image' => 'https://img.freepik.com/free-photo/closeup-belt_53876-33678.jpg',
            'price' => 13.99,
            'old_price' => 19.99,
            'rating' => 3,
        ],
        [
            'name' => 'Moisturizing Cream',
            'image' => 'https://img.freepik.com/free-vector/vector-3d-realistic-advertising-mock-up_33099-1242.jpg?t=st=1751484551~exp=1751488151~hmac=05e140bb0d2023097e426b8d73e2cbd77cd3041b7b666a43dc5aaed4f2b31796&w=1800',
            'price' => 17.49,
            'old_price' => 24.99,
            'rating' => 4,
        ],
        [
            'name' => 'Hair Dryer',
            'image' => 'https://img.freepik.com/free-psd/cosmetic-makeup-icon-design_23-2151874126.jpg?t=st=1751484594~exp=1751488194~hmac=722d1800683e0ef488fc353cdb64863aa72512f447e3078988c3e7e7482d2bad&w=1800',
            'price' => 29.99,
            'old_price' => 44.99,
            'rating' => 5,
        ],
        [
            'name' => 'Black Sunglasses',
            'image' => 'https://img.freepik.com/free-photo/sunglasses_1203-7884.jpg?t=st=1751484640~exp=1751488240~hmac=fc50b314baf2441d7a8441afd029c1cd285ee841d47c9111db4ca756a01442f1&w=1800',
            'price' => 15.25,
            'old_price' => 21.00,
            'rating' => 4,
        ],
        [
            'name' => 'Classic Perfume',
            'image' => 'https://img.freepik.com/free-photo/male-self-care-items-arrangement_23-2150347135.jpg?t=st=1751484753~exp=1751488353~hmac=8df8cea1b9c7ebcddc36319dfc8627d901ef123a2999c316925919794dcaa796&w=1800',
            'price' => 27.50,
            'old_price' => 39.99,
            'rating' => 5,
        ],
    ];

@endphp

<section class="max-w-[1100px] mx-auto my-5">
    <div class="flex justify-between items-center border-b border-b-gray-400 pb-1">
        <span class="text-lg font-semibold">Latest Products</span>
        <a href="#view-all" class="text-[#007025] hover:underline">View All</a>
    </div>

    <ul class="grid  grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mt-5">
        @foreach ($products as $product)
            <li class="">
                <div class="rounded-lg bg-white shadow-sm flex flex-col justify-between h-[366px] min-w-0 
                                transition-all duration-300 transform hover:scale-[1.02] hover:shadow-md">

                    {{-- Product Image --}}
                    <div class="overflow-hidden rounded-t-lg">
                        <img src="{{ $product['image'] }}" onerror="this.src='https://via.placeholder.com/150'"
                            alt="{{ $product['name'] }}"
                            class="w-full h-52 object-cover transition-transform duration-300 hover:scale-105" />
                    </div>

                    {{-- Product Details --}}
                    <div class="p-3 flex-1 flex flex-col justify-between">
                        {{-- Rating --}}
                        <div class="text-yellow-400 text-sm mb-1">
                            @for ($i = 0; $i < $product['rating']; $i++)
                                ★
                            @endfor
                            @for ($i = $product['rating']; $i < 5; $i++)
                                <span class="text-gray-300">★</span>
                            @endfor
                        </div>

                        {{-- Price --}}
                        <div class="text-sm mb-1">
                            <span class="text-red-600 font-semibold">${{ number_format($product['price'], 2) }}</span>
                            <span
                                class="line-through text-gray-400 ml-1 text-xs">${{ number_format($product['old_price'], 2) }}</span>
                        </div>

                        {{-- Product Name --}}
                        <a href="{{ route('new.product.show', 9876732) }}" class="text-gray-800 text-sm font-semibold text-[#007025] hover:underline">
                            {{ $product['name'] }}
                        </a>
                    </div>

                    {{-- CTA Buttons --}}
                    <div class="flex gap-5 mt-2 p-3">
                        <button class="inline-flex cursor-pointer bg-gray-200 text-white text-xs px-2 py-1 rounded 
                                           transition-all duration-200 hover:bg-gray-100 justify-center items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="25" height="25" x="0" y="0" viewBox="0 0 450.391 450.391"
                                style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                <g>
                                    <path
                                        d="M143.673 350.322c-25.969 0-47.02 21.052-47.02 47.02 0 25.969 21.052 47.02 47.02 47.02 25.969 0 47.02-21.052 47.02-47.02.001-25.968-21.051-47.02-47.02-47.02zm0 73.143c-14.427 0-26.122-11.695-26.122-26.122s11.695-26.122 26.122-26.122 26.122 11.695 26.122 26.122c.001 14.427-11.695 26.122-26.122 26.122zM342.204 350.322c-25.969 0-47.02 21.052-47.02 47.02 0 25.969 21.052 47.02 47.02 47.02s47.02-21.052 47.02-47.02c0-25.968-21.051-47.02-47.02-47.02zm0 73.143c-14.427 0-26.122-11.695-26.122-26.122s11.695-26.122 26.122-26.122 26.122 11.695 26.122 26.122c.001 14.427-11.695 26.122-26.122 26.122zM448.261 76.037a13.064 13.064 0 0 0-8.359-4.18L99.788 67.155 90.384 38.42C83.759 19.211 65.771 6.243 45.453 6.028H10.449C4.678 6.028 0 10.706 0 16.477s4.678 10.449 10.449 10.449h35.004a27.17 27.17 0 0 1 25.078 18.286l66.351 200.098-5.224 12.016a50.154 50.154 0 0 0 4.702 45.453 48.588 48.588 0 0 0 39.184 21.943h203.233c5.771 0 10.449-4.678 10.449-10.449s-4.678-10.449-10.449-10.449H175.543a26.646 26.646 0 0 1-21.943-12.539 28.733 28.733 0 0 1-2.612-25.078l4.18-9.404 219.951-22.988c24.16-2.661 44.034-20.233 49.633-43.886L449.83 84.917a8.882 8.882 0 0 0-1.569-8.88zm-43.885 109.191c-3.392 15.226-16.319 26.457-31.869 27.69l-217.339 22.465-48.588-147.33 320.261 4.702-22.465 92.473z"
                                        fill="#007025" opacity="1" data-original="#000000" class=""></path>
                                </g>
                            </svg>
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
</section>