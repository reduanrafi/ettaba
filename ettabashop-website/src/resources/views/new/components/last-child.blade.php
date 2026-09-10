@php
$products = [
    [
        'name' => 'Basic White T-Shirt',
        'image' => 'https://img.freepik.com/free-vector/simple-monocolor-home-run-hero-baseball-t-shirt_742173-8050.jpg',
        'price' => 14.99,
        'old_price' => 19.99,
        'rating' => 4,
    ],
    [
        'name' => 'Black Graphic Tee',
        'image' => 'https://img.freepik.com/free-photo/shirt-mockup-concept-with-plain-clothing_23-2149448748.jpg?t=st=1751487095~exp=1751490695~hmac=fef2918ee9e58ae9c4a5a0a4c32e1f63db4093938bf8af64da0bb05a5b259db5&w=1800',
        'price' => 17.50,
        'old_price' => 24.99,
        'rating' => 5,
    ],
    [
        'name' => 'Oversized Beige T-Shirt',
        'image' => 'https://img.freepik.com/free-photo/smiling-young-pretty-caucasian-girl-sun-glasses-with-headphones-around-neck-thumbs-up-isolated-olive-green-wall-with-copy-space_141793-118978.jpg?t=st=1751487132~exp=1751490732~hmac=99ad9ff7b4eb9e5e5d19cf6571be1d1f0044bfec87bf6b6cf2c82526b2097fa7&w=1800',
        'price' => 22.00,
        'old_price' => 29.99,
        'rating' => 4,
    ],
    [
        'name' => 'Couple Cotton Tee',
        'image' => 'https://img.freepik.com/free-photo/portrait-young-couple-yellow_158595-5534.jpg?t=st=1751487179~exp=1751490779~hmac=2b739a800c2044b16a1e378b2d7ef46104e93a20500e0a1009a98ef3aa328299&w=1800',
        'price' => 18.75,
        'old_price' => 25.00,
        'rating' => 3,
    ],
    [
        'name' => 'Maroon Polo Shirt',
        'image' => 'https://img.freepik.com/free-photo/confident-young-handsome-guy-wearing-red-shirt-glasses-showing-peace-gesture-isolated-green-wall_141793-82423.jpg?t=st=1751487343~exp=1751490943~hmac=b3b84a95141830b1890d7a2af517cf3833bfb1bd4f4685e518be6c463a848491&w=1800',
        'price' => 21.99,
        'old_price' => 31.99,
        'rating' => 5,
    ],
    [
        'name' => 'Green Vintage T-Shirt',
        'image' => 'https://img.freepik.com/free-photo/view-football-player-t-shirt_23-2150885803.jpg?t=st=1751487282~exp=1751490882~hmac=e172c7a03c5471db27d05747b8de79f31d95f113f67e9b62f85462cda3bf58c4&w=1800',
        'price' => 16.50,
        'old_price' => 22.50,
        'rating' => 4,
    ],
];
@endphp

<section class="max-w-[1100px] mx-auto my-5">
    <div class="flex justify-between items-center border-b border-b-gray-400 pb-1">
        <span class="text-lg font-semibold">T Shirt</span>
        <a href="#view-all" class="text-[#007025] hover:underline">View All</a>
    </div>

    <ul class="grid  grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach ($products as $product)
            <li class="my-5">
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