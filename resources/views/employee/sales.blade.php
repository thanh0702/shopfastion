@extends('admin.layout')

@section('title', 'Employee Sales Page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar for Categories -->
        <div class="col-md-3">
            <h5>Filter by Category</h5>
            <form method="GET" action="{{ route('employee.sales') }}">
                <div class="list-group">
                    <a href="{{ route('employee.sales') }}" class="list-group-item list-group-item-action {{ empty($selectedCategory) ? 'active' : '' }}">
                        All Categories
                    </a>
                    @foreach($categories as $category)
                        <button type="submit" name="category" value="{{ $category->id }}" class="list-group-item list-group-item-action {{ ($selectedCategory == $category->id) ? 'active' : '' }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="search" value="{{ $search ?? '' }}">
            </form>
        </div>

        <!-- Main content for Products -->
        <div class="col-md-9">
            <form method="GET" action="{{ route('employee.sales') }}" class="mb-3 d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search products..." value="{{ $search ?? '' }}">
                <input type="hidden" name="category" value="{{ $selectedCategory ?? '' }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <div class="row row-cols-1 row-cols-md-5 g-3">
                @foreach($products as $product)
                    <div class="col">
                        <div class="card h-100 product-card">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @else
                            <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="{{ $product->name }}">
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">{{ $product->name }}</h6>
                                <p class="card-text">{{ number_format($product->price, 0, '.', ',') }} VND</p>
                            </div>
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
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
</style>
@endsection
