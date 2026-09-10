<div class="sidebar-card bg-white h-100">
    <div class="sidebar-header">
        <i class="fas fa-bars mr-2 text-primary"></i> Categories
    </div>
    <div class="list-group list-group-flush">
        @foreach(['Electronics', 'Fashion', 'Home & Garden', 'Beauty', 'Sports', 'Toys', 'Automotive', 'Books'] as $category)
            <a href="#" class="category-link border-bottom-0">
                <span>{{ $category }}</span>
                <i class="fas fa-chevron-right small text-muted"></i>
            </a>
        @endforeach
    </div>
</div>