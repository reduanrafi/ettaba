@php
$brands = [
    [
        'name' => 'Nike',
        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/a/a6/Logo_NIKE.svg',
    ],
    [
        'name' => 'Adidas',
        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/2/20/Adidas_Logo.svg',
    ],
    [
        'name' => 'Zara',
        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fd/Zara_Logo.svg/500px-Zara_Logo.svg.png',
    ],
    [
        'name' => 'H&M',
        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/5/53/H%26M-Logo.svg',
    ],
    [
        'name' => 'Uniqlo',
        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/Uniqlo_logo_Japanese.svg/500px-Uniqlo_logo_Japanese.svg.png',
    ],
    [
        'name' => 'Levi\'s',
        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/09/Levis-logo-quer.svg/360px-Levis-logo-quer.svg.png',
    ],
    [
        'name' => 'Gucci',
        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/9/9b/Gucci_logo.png',
    ],
];

@endphp
<section class="max-w-[1100px] mx-auto my-10">
  <div class="flex justify-between items-center border-b border-b-gray-400 pb-1">
        <span class="text-lg font-semibold">Shop By Brands</span>
        <a href="#view-all" class="text-[#007025] hover:underline">View All</a>
    </div>

  <div class="grid my-2  grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-6">
    @foreach ($brands as $brand)
      <a href="#" class="bg-white rounded-2xl shadow-md p-4 flex flex-col items-center justify-center transition-transform duration-300 hover:scale-105 hover:shadow-lg">
        <img src="{{ $brand['logo'] }}" alt="{{ $brand['name'] }} Logo" class="h-10 object-contain mb-3" />
        <!-- <p class="text-sm font-semibold text-gray-700">{{ $brand['name'] }}</p> -->
      </a>
    @endforeach
  </div>
</section>
