<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Shop - Shop Thời Trang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
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
        }
        .product-image.second {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
        }
        .product-card:hover .product-image.first {
            opacity: 0;
        }
        .product-card:hover .product-image.second {
            opacity: 1;
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="container my-5">
        <h2 class="text-center mb-4">Shop</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="card p-3 mb-4">
                    <h5>Sắp xếp</h5>
                    <form method="GET" action="{{ route('shop') }}">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort" id="sort_default" value="default" {{ (!isset($sort) || $sort == 'default') ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="sort_default">
                                Mặc định
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort" id="sort_name_asc" value="name_asc" {{ (isset($sort) && $sort == 'name_asc') ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="sort_name_asc">
                                Tên A-Z
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort" id="sort_name_desc" value="name_desc" {{ (isset($sort) && $sort == 'name_desc') ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="sort_name_desc">
                                Tên Z-A
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort" id="sort_price_asc" value="price_asc" {{ (isset($sort) && $sort == 'price_asc') ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="sort_price_asc">
                                Giá thấp đến cao
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sort" id="sort_price_desc" value="price_desc" {{ (isset($sort) && $sort == 'price_desc') ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="sort_price_desc">
                                Giá cao xuống thấp
                            </label>
                        </div>
                    </form>
                </div>
                <div class="card p-3">
                    <h5>Danh mục</h5>
                    <form method="GET" action="{{ route('shop') }}">
                        <input type="hidden" name="sort" value="{{ $sort ?? 'default' }}">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="category_all" value="" {{ (!isset($categoryId) || $categoryId == '') ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="category_all">
                                Tất cả danh mục
                            </label>
                        </div>
                        @foreach ($categories as $cat)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="category_{{ $cat->id }}" value="{{ $cat->id }}" {{ (isset($categoryId) && $categoryId == $cat->id) ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="category_{{ $cat->id }}">
                                {{ $cat->name }}
                            </label>
                        </div>
                        @endforeach
                    </form>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @foreach ($products as $product)
                    <div class="col">
                        <div class="card h-100 product-card">
                            <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                                <div class="product-image-container">
                                    @if ($product->images && is_array($product->images) && count($product->images) > 1)
                                    <img src="{{ $product->images[0] }}" class="card-img-top product-image first" alt="{{ $product->name }}" />
                                    <img src="{{ $product->images[1] }}" class="card-img-top product-image second" alt="{{ $product->name }}" />
                                    @elseif ($product->images && is_array($product->images) && count($product->images) > 0)
                                    <img src="{{ $product->images[0] }}" class="card-img-top product-image first" alt="{{ $product->name }}" />
                                    @elseif ($product->image_url)
                                    <img src="{{ $product->image_url }}" class="card-img-top product-image first" alt="{{ $product->name }}" />
                                    @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        No Image
                                    </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-dark">{{ $product->name }}</h5>
                                    <p class="card-text text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }} VND</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     @include('partials.footer')
</body>
</html>
