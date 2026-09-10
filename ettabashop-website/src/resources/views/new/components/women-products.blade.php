@php
    $products = [
        [
            'name' => 'Floral Summer Dress',
            'image' => 'https://img.freepik.com/premium-photo/fashionable-young-woman-looking-away-while-standing-against-flowering-plants_1048944-13687817.jpg?w=1800',
            'rating' => 5,
            'price' => 49.99,
            'old_price' => 69.99,
        ],
        [
            'name' => 'Elegant Evening Gown',
            'image' => 'https://img.freepik.com/free-photo/front-view-elegant-girl-red-dress-leans-chair-looks-camera_8353-11655.jpg?t=st=1751482427~exp=1751486027~hmac=9a7ad1ec456ea82498af78e0c801830857d743142824657947b6ac5786f000f6&w=1800',
            'rating' => 4,
            'price' => 89.99,
            'old_price' => 119.99,
        ],
        [
            'name' => 'Casual Denim Dress',
            'image' => 'https://img.freepik.com/free-photo/attractive-girls-posing-pink-background-denim-overall-fashion-trend_285396-7176.jpg?t=st=1751482479~exp=1751486079~hmac=aa509019c8947eb9579c0885d81e13bff1a01a24106bf50de54253fe5b5679bc&w=1800',
            'rating' => 4,
            'price' => 39.99,
            'old_price' => 59.99,
        ],
        [
            'name' => 'Boho Maxi Dress',
            'image' => 'https://img.freepik.com/free-photo/front-view-beautiful-women-bare-feet_23-2148261264.jpg?t=st=1751482538~exp=1751486138~hmac=ab6c3e7ef8dcad39da81efb138dd8b00752d4021bb5e2c7d47935ef16df4f1ee&w=1800',
            'rating' => 5,
            'price' => 54.99,
            'old_price' => 79.99,
        ],
        [
            'name' => 'Little Black Dress',
            'image' => 'https://img.freepik.com/free-photo/full-length-shot-young-beautiful-elegant-lady-wearing-black-dress-walking-city-street-style-fashion-concept_132075-5886.jpg?t=st=1751482587~exp=1751486187~hmac=f8e45d4892b5218e815da0c6248caec288b406de5e153e8b1ee3de3bf720ef9f&w=1800',
            'rating' => 5,
            'price' => 59.99,
            'old_price' => 89.99,
        ],
        [
            'name' => 'Summer Shirt Dress',
            'image' => 'https://img.freepik.com/free-photo/young-beautiful-woman-wearing-summer-hat-red-sunglasses-with-confident-smile-blue-wall_141793-13469.jpg?t=st=1751482653~exp=1751486253~hmac=c1fbd2b829cc743782d7e614b519e132093800d150508c252b163b96c26248d8&w=1800',
            'rating' => 4,
            'price' => 44.99,
            'old_price' => 64.99,
        ],
    ];
@endphp

<section class="max-w-[1100px] mx-auto my-5">
    <div class="flex justify-between items-center border-b border-b-gray-400 pb-1">
        <span class="text-lg font-semibold">Women Collections</span>
        <a href="#view-all" class="text-[#007025] hover:underline">View All</a>
    </div>

    <div class="women-glide mt-4">
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


<style>
    .rtl-glide {
  direction: rtl;
}

.rtl-glide .glide__slides {
  flex-direction: row-reverse;
}
</style>
<script>
    new Glide('.women-glide', {
        type: 'carousel',
        direction: 'rtl',
        startAt: 0,
        perView: 6,
        gap: 10,
        autoplay: 2000,
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