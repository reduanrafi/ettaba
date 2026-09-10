<div class="category-sidebar modern-card p-0 overflow-hidden shadow-sm border-0">
    <div class="bg-primary text-white p-3 d-flex align-items-center">
        <i class="fas fa-list-ul mr-3"></i>
        <h6 class="m-0 font-weight-bold">{{__('menu.categories')}}</h6>
    </div>

    <nav class="navbar navbar-light p-0">
        <div class="navbar-nav w-100 overflow-auto" style="max-height: 380px;">
            @foreach($categories as $category)
                @if(count($category->childrenCategories) > 0)
                    <div class="nav-item dropdown">
                        <a href="#"
                            class="nav-link category-link px-4 py-3 border-bottom d-flex justify-content-between align-items-center"
                            data-toggle="dropdown">
                            <span>{{ $local == 'bn' ? $category->name_bn : $category->name_en }}</span>
                            <i class="fa fa-angle-right small text-muted"></i>
                        </a>
                        <div class="dropdown-menu border-0 shadow-lg m-0 p-2 rounded-lg"
                            style="left: 100%; top: 0; min-width: 200px;">
                            <a href="{{ route('categoryProducts', ['slug' => $category->slug]) }}"
                                class="dropdown-item rounded-pill font-weight-bold text-primary mb-1">
                                All in {{ $local == 'bn' ? $category->name_bn : $category->name_en }}
                            </a>
                            <div class="dropdown-divider"></div>
                            @foreach($category->childrenCategories as $subCategory)
                                <a href="{{ route('categoryProducts', ['slug' => $subCategory->slug]) }}"
                                    class="dropdown-item rounded-pill">
                                    {{ $local == 'bn' ? $subCategory->name_bn : $subCategory->name_en }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ route('categoryProducts', ['slug' => $category->slug]) }}"
                        class="nav-item nav-link category-link px-4 py-3 border-bottom">
                        {{ $local == 'bn' ? $category->name_bn : $category->name_en }}
                    </a>
                @endif
            @endforeach
        </div>
    </nav>
</div>

<style>
    .category-link {
        font-weight: 600;
        color: var(--text-main) !important;
        transition: var(--transition);
        border-left: 3px solid transparent;
    }

    .category-link:hover {
        background: #f8fafc;
        color: var(--primary) !important;
        border-left-color: var(--primary);
        padding-left: 1.75rem !important;
    }

    .dropdown:hover>.dropdown-menu {
        display: block;
    }
</style>