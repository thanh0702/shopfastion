@extends('admin.layout')

@section('title', 'Employee Sales Page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar for Categories and Cart Button -->
        <div class="col-md-3">

            <!-- Cart Button -->
            <div class="card p-3 mb-4 d-flex flex-column align-items-start">
                <a href="{{ route('employee.cart') }}" class="btn btn-warning position-relative mb-3" style="width: 100%; font-weight: bold;">
                    Giỏ hàng nhân viên
                    <span id="employee-cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $cartCount ?? 0 }}
                        <span class="visually-hidden">items in cart</span>
                    </span>
                </a>

                <a href="{{ route('employee.orders') }}" class="btn btn-info position-relative mb-3" style="width: 100%; font-weight: bold;">
                    <i class="bi bi-receipt"></i> Đơn hàng của tôi
                </a>

                <a href="{{ route('employee.all-orders') }}" class="btn btn-primary position-relative mb-3" style="width: 100%; font-weight: bold;">
                    <i class="bi bi-list-ul"></i> Quản lý tất cả đơn hàng
                </a>

                <a href="{{ route('employee.products.index') }}" class="btn btn-success position-relative mb-3" style="width: 100%; font-weight: bold;">
                    <i class="bi bi-box-seam"></i> Quản lý sản phẩm
                </a>

                 <a href="{{ route('employee.suppliers.index') }}" class="btn btn-info position-relative mb-3" style="width: 100%; font-weight: bold;">
                    <i class="bi bi-building"></i> Quản lý nhà cung cấp
                </a>
            </div>

            <!-- Category List -->
            <div class="card p-3">
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
                    <div class="card h-100 product-card position-relative">
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

                                <!-- Add to Cart Button shown on hover -->
                                <button class="btn btn-success add-to-cart-btn position-absolute top-50 start-50 translate-middle d-none"
                                    data-product-id="{{ $product->_id }}" style="opacity: 0.9;">
                                    Thêm vào giỏ hàng
                                </button>
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

        /* Show Add to Cart button only on hover */
        .product-card:hover .add-to-cart-btn {
            display: block !important;
        }

        /* Styling for cart count badge button on sidebar */
        #employee-cart-count {
            font-size: 0.85rem;
            padding: 0.35em 0.55em;
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add to Cart button click handler
            const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const productId = this.getAttribute('data-product-id');

                    fetch("/employee/cart/add", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update cart count badge
                            document.getElementById('employee-cart-count').textContent = data.cartCount;
                            alert('Sản phẩm đã được thêm vào giỏ hàng!');
                        } else {
                            alert('Lỗi khi thêm sản phẩm vào giỏ hàng.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Lỗi khi thêm sản phẩm vào giỏ hàng.');
                    });
                });
            });
        });
        </script>

@endsection
