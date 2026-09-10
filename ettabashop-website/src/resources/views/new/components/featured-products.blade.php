@php
    $products = [
        [
            'name' => 'T Shirt',
            'image' => 'https://img.freepik.com/free-vector/flat-modern-baseball-t-shirt_742173-10696.jpg?t=st=1751480404~exp=1751484004~hmac=9ff197fbcf30aba55613d03c208a5028bcd99c98628e29436f57f5f752fb35c8&w=1800',
            'rating' => 5,
            'price' => 49.99,
            'old_price' => 59.99,
        ],
        [
            'name' => 'Adidas Polo Combo',
            'image' => 'https://img.freepik.com/premium-photo/polo-shirts-different-colors_1318039-2244.jpg?w=1800',
            'rating' => 5,
            'price' => 119.99,
            'old_price' => 149.99,
        ],
        [
            'name' => 'Wireless Headphones',
            'image' => 'https://img.freepik.com/free-photo/headphones-displayed-against-dark-background_157027-4466.jpg?t=st=1751480250~exp=1751483850~hmac=de9dd9e091763df51e41421c4141018d078b940113b7f56c6f0a712f6c2bd6cc&w=1800',
            'rating' => 4,
            'price' => 39.99,
            'old_price' => 59.99,
        ],
        [
            'name' => 'Smart Watch',
            'image' => 'https://img.freepik.com/free-photo/rendering-smart-home-device_23-2151039302.jpg?ga=GA1.1.1723312869.1751479298&semt=ais_hybrid&w=740',
            'rating' => 5,
            'price' => 49.99,
            'old_price' => 79.99,
        ],
        [
            'name' => 'Bluetooth Speaker',
            'image' => 'https://img.freepik.com/free-photo/high-angle-smart-speaker-home_23-2150171766.jpg?t=st=1751480383~exp=1751483983~hmac=b0e99b88918c92d34fdda9f15a87f11a3fb757e1940ec2a46703b86127aa2113&w=1800',
            'rating' => 4,
            'price' => 24.99,
            'old_price' => 39.99,
        ],
        [
            'name' => 'Gaming Mouse',
            'image' => 'https://img.freepik.com/free-photo/ai-powered-device-concept_23-2151924152.jpg?t=st=1751480512~exp=1751484112~hmac=78d428776aec5b40f4b017f5f3bc62a8800673eac35454d32369fd82395c286e&w=1800',
            'rating' => 4,
            'price' => 19.99,
            'old_price' => 29.99,
        ],
        [
            'name' => 'Portable Charger',
            'image' => 'https://img.freepik.com/free-photo/smartphone-is-charged-from-small-white-power-bank-via-cord-close-up_169016-15462.jpg?t=st=1751480568~exp=1751484168~hmac=66df23113072d1f204d0d1374222107ac3edbe95ea43ec893b5bd47db35dfc44&w=1800',
            'rating' => 5,
            'price' => 29.99,
            'old_price' => 44.99,
        ],
        [
            'name' => 'LED Monitor',
            'image' => 'https://img.freepik.com/free-psd/modern-tv-screen-isolated_23-2151430324.jpg?t=st=1751480444~exp=1751484044~hmac=c4a3ae9ab8ae6505c8a0cdb03eee5d9c986042f2982432986de68f2000f89a17&w=1800',
            'rating' => 4,
            'price' => 129.99,
            'old_price' => 159.99,
        ],
    ];
@endphp

<section class="max-w-[1100px] mx-auto my-5">
    <div class="flex justify-between items-center border-b border-b-gray-400 pb-1">
        <span class="text-lg font-semibold">Featured Products</span>
        <a href="#view-all" class="text-[#007025] hover:underline">View All</a>
    </div>

    <div class="featured-glide mt-4">
        <div class="glide__track" data-glide-el="track">
            <ul class="glide__slides">
                @foreach ($products as $product)
                    <li class="glide__slide">
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
                                    <span
                                        class="text-red-600 font-semibold">${{ number_format($product['price'], 2) }}</span>
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

</section>

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