<!-- Online Categories Section (Specification Part C - Section 10) -->
<div class="online-categories-wrap mb-4">
    <div class="categories-header d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <div class="cat-header-icon mr-2">
                <i class="fas fa-th-large"></i>
            </div>
            <div>
                <h5 class="cat-header-title mb-0">অনলাইন ক্যাটাগরি</h5>
                <span class="text-muted" style="font-size: 12px;">প্রয়োজনীয় পণ্য ও সেবার বিভিন্ন বিভাগসমূহ</span>
            </div>
        </div>
        <span class="badge badge-light text-muted border px-2 py-1" style="font-size: 11px;">
            <i class="fas fa-store mr-1 text-primary"></i> শপ ক্যাটাগরি
        </span>
    </div>

    <div class="categories-card p-3 p-md-4">
        <div class="online-cat-grid">
            @php
                // Default fallback icons if needed
                $fallbackIcons = [
                    'গ্রোসারি' => ['icon' => 'fas fa-shopping-basket', 'bg' => '#ecfdf5', 'color' => '#059669'],
                    'কাঁচা বাজার' => ['icon' => 'fas fa-carrot', 'bg' => '#fff7ed', 'color' => '#ea580c'],
                    'মাছ-মাংস' => ['icon' => 'fas fa-fish', 'bg' => '#eff6ff', 'color' => '#2563eb'],
                    'খাবার ও রেস্টুরেন্ট' => ['icon' => 'fas fa-utensils', 'bg' => '#fef2f2', 'color' => '#dc2626'],
                    'রিচার্জ, ডাটা ও মিনিট' => ['icon' => 'fas fa-mobile-alt', 'bg' => '#f5f3ff', 'color' => '#7c3aed'],
                    'ঔষধ ও ফার্মেসী' => ['icon' => 'fas fa-pills', 'bg' => '#f0fdf4', 'color' => '#16a34a'],
                    'অর্গানিক পণ্য' => ['icon' => 'fas fa-seedling', 'bg' => '#ecfdf5', 'color' => '#10b981'],
                    'ফ্যাশন' => ['icon' => 'fas fa-tshirt', 'bg' => '#fdf2f8', 'color' => '#db2777'],
                    'ইলেক্ট্রনিক' => ['icon' => 'fas fa-tv', 'bg' => '#eef2ff', 'color' => '#4f46e5'],
                    'মোবাইল' => ['icon' => 'fas fa-mobile', 'bg' => '#f0f9ff', 'color' => '#0284c7'],
                    'হস্তশিল্প' => ['icon' => 'fas fa-paint-brush', 'bg' => '#fffbeb', 'color' => '#d97706'],
                    'আরো দেখুন' => ['icon' => 'fas fa-ellipsis-h', 'bg' => '#f1f5f9', 'color' => '#475569'],
                ];
            @endphp

            @if(isset($onlineCategories) && $onlineCategories->count() > 0)
                @foreach($onlineCategories as $cat)
                    @php
                        $meta = $fallbackIcons[$cat->name] ?? ['icon' => $cat->icon ?: 'fas fa-th', 'bg' => '#f8fafc', 'color' => '#2563eb'];
                        $iconClass = !empty($cat->icon) ? $cat->icon : $meta['icon'];
                    @endphp
                    <div class="cat-grid-item" onclick="categoryItemClick('{{ addslashes($cat->name) }}')">
                        <div class="cat-icon-circle" style="background-color: {{ $meta['bg'] }}; color: {{ $meta['color'] }};">
                            <i class="{{ $iconClass }}"></i>
                        </div>
                        <span class="cat-item-name">{{ $cat->name }}</span>
                    </div>
                @endforeach
            @else
                @foreach($fallbackIcons as $name => $meta)
                    <div class="cat-grid-item" onclick="categoryItemClick('{{ addslashes($name) }}')">
                        <div class="cat-icon-circle" style="background-color: {{ $meta['bg'] }}; color: {{ $meta['color'] }};">
                            <i class="{{ $meta['icon'] }}"></i>
                        </div>
                        <span class="cat-item-name">{{ $name }}</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<style>
/* Online Categories Modern Responsive Grid */
.online-categories-wrap {
    margin-top: 5px;
}
.cat-header-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
    color: #fff;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
}
.cat-header-title {
    font-size: 17px;
    font-weight: 700;
    color: #1e293b;
}
.categories-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.02);
}
.online-cat-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px 12px;
}
@media (max-width: 991px) {
    .online-cat-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 14px 10px;
    }
}
@media (max-width: 575px) {
    .online-cat-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 12px 6px;
    }
}
.cat-grid-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    cursor: pointer;
    padding: 8px 4px;
    border-radius: 14px;
    transition: all 0.22s ease-in-out;
    user-select: none;
}
.cat-grid-item:hover {
    transform: translateY(-3px);
    background: #f8fafc;
}
.cat-grid-item:active {
    transform: scale(0.96);
}
.cat-icon-circle {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 8px;
    transition: all 0.2s ease;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.04);
}
@media (max-width: 575px) {
    .cat-icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        font-size: 19px;
        margin-bottom: 6px;
    }
}
.cat-grid-item:hover .cat-icon-circle {
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
}
.cat-item-name {
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    max-width: 100%;
}
@media (max-width: 575px) {
    .cat-item-name {
        font-size: 11px;
    }
}
</style>

<script>
function categoryItemClick(name) {
    if (typeof showToast === 'function') {
        showToast(name + ' ক্যাটাগরি শীঘ্রই চালু হচ্ছে!');
    } else {
        alert(name + ' ক্যাটাগরি শীঘ্রই চালু হচ্ছে!');
    }
}
</script>
