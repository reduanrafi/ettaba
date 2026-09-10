@php
   $products = [
    [
        'name' => 'Hydrating Face Serum',
        'image' => 'https://img.freepik.com/free-photo/healthy-argan-oil-composition_23-2148989081.jpg?t=st=1751483013~exp=1751486613~hmac=9a7ee900b43943a67f884923a8c3e3f7f1ea1cae6669e2a4c27aaf1b2e7217ac&w=1800',
        'price' => 19.99,
        'old_price' => 29.99,
        'rating' => 4,
    ],
    [
        'name' => 'Matte Liquid Lipstick',
        'image' => 'https://img.freepik.com/free-photo/applying-red-lipstick-lips-close-up-photo-black-background-beauty-make-up_482257-10395.jpg?t=st=1751483085~exp=1751486685~hmac=abecd6b36ebf0e23f40107688ab4960a140db3422354593a15ca5b072641d9df&w=1800',
        'price' => 9.99,
        'old_price' => 14.99,
        'rating' => 5,
    ],
    [
        'name' => 'Natural Glow Foundation',
        'image' => 'https://img.freepik.com/free-photo/combination-makeup-textures_23-2150039180.jpg?t=st=1751483118~exp=1751486718~hmac=2f8d62d5066e9b4d2fb81a53c8e9e59e202b6a9e14917b8be84cb10bb8ce7c2a&w=1800',
        'price' => 24.99,
        'old_price' => 34.99,
        'rating' => 3,
    ],
    [
        'name' => 'Volumizing Mascara',
        'image' => 'https://img.freepik.com/free-photo/cute-beautiful-girl-dye-eyelashes-smiling-looking-camera-white-background-beauty-health-cosmetology-concept_176420-14015.jpg?t=st=1751483166~exp=1751486766~hmac=995c032690678e2ece5aa05fec658bfdd5e27a215c48be6d35387a0450b36d43&w=1800',
        'price' => 12.49,
        'old_price' => 17.99,
        'rating' => 4,
    ],
    [
        'name' => 'Rose Water Toner',
        'image' => 'https://img.freepik.com/free-photo/beautiful-roses-petals-arrangement_23-2150787646.jpg?t=st=1751483240~exp=1751486840~hmac=fab75b178353d0a252960666beb46587e655f6655033202a4d8a5e1ac33cf51e&w=1800',
        'price' => 11.99,
        'old_price' => 15.99,
        'rating' => 4,
    ],
    [
        'name' => 'Aloe Vera Moisturizer',
        'image' => 'https://img.freepik.com/free-photo/aloe-vera-cosmetic-cream-white-surface_1150-42275.jpg?t=st=1751483282~exp=1751486882~hmac=2bb24e7853e2bd8aa5cfcbae5dcdb98aa0a67ffda77de10d2b3980508f67e146&w=1800',
        'price' => 15.49,
        'old_price' => 20.00,
        'rating' => 5,
    ],
];

@endphp

<section class="max-w-[1100px] mx-auto my-5">
    <div class="flex justify-between items-center border-b border-b-gray-400 pb-1">
        <span class="text-lg font-semibold">Beauty</span>
        <a href="#view-all" class="text-[#007025] hover:underline">View All</a>
    </div>

     <div class="beauty-glide mt-4">
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
    new Glide('.beauty-glide', {
        type: 'carousel',
        direction: 'ltr',
        startAt: 0,
        perView: 6,
        gap: 10,
        autoplay: 2500,
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