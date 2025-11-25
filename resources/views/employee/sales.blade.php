@extends('admin.layout')

@section('title', 'Employee Sales Page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar for Categories -->
        <div class="col-md-3">
            <div class="card p-3 mb-4">
                <h5>Danh Mục</h5>
                <form method="GET" action="{{ route('employee.sales') }}">
                    <input type="hidden" name="search" value="{{ $search ?? '' }}">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" id="category_all" value="" {{ empty($selectedCategory) ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="form-check-label" for="category_all">Tất cả danh mục</label>
                    </div>
                    @foreach ($categories as $category)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" id="category_{{ $category->id }}" value="{{ $category->id }}" {{ (isset($selectedCategory) && $selectedCategory == $category->id) ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="form-check-label" for="category_{{ $category->id }}">{{ $category->name }}</label>
                    </div>
                    @endforeach
                </form>
            </div>
        </div>

        <!-- Main content for Products -->
        <div class="col-md-9">
            <form method="GET" action="{{ route('employee.sales') }}" class="mb-3 d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm sản phẩm..." value="{{ $search ?? '' }}">
                <input type="hidden" name="category" value="{{ $selectedCategory ?? '' }}">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </form>

            <div class="row row-cols-1 row-cols-md-5 g-3">
                @foreach($products as $product)
                <div class="col">
                    <div class="card h-100 product-card">
                        <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                            <div class="product-image-container">
                                @if ($product->images && is_array($product->images) && count($product->images) > 1)
                                <img src="{{ $product->images[0] }}" class="card-img-top product-image first" alt="{{ $product->name }}">
                                <img src="{{ $product->images[1] }}" class="card-img-top product-image second" alt="{{ $product->name }}">
                                @elseif ($product->images && is_array($product->images) && count($product->images) > 0)
                                <img src="{{ $product->images[0] }}" class="card-img-top product-image first" alt="{{ $product->name }}">
                                @elseif ($product->image_url)
                                <img src="{{ $product->image_url }}" class="card-img-top product-image first" alt="{{ $product->name }}">
                                @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    No Image
                                </div>
                                @endif
                            </div>
                            <div class="card-body p-2">
                                <h5 class="card-title text-dark mb-1" style="font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product->name }}</h5>
                                <p class="card-text text-danger fw-bold mb-0" style="font-size: 0.9rem;">{{ number_format($product->price, 0, ',', '.') }} VND</p>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-3">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}
.product-image-container {
    position: relative;
    overflow: hidden;
}
.product-image {
    transition: opacity 0.3s ease;
    height: 150px;
    object-fit: contain;
}
.product-image.second {
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
    height: 150px;
    object-fit: contain;
}
.product-card:hover .product-image.first {
    opacity: 0;
}
.product-card:hover .product-image.second {
    opacity: 1;
}
</style>

@endsection
