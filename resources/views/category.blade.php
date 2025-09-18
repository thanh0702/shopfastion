<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $category->name }} - Shop Thời Trang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
</head>
<body>
    @include('partials.header')

    <div class="container my-5">
        <h2 class="text-center mb-4">{{ $category->name }}</h2>
        @if($category->description)
            <p class="text-center">{{ $category->description }}</p>
        @endif
        <div class="row">
            <div class="col-md-3">
                <div class="card p-3 mb-4">
                    <h5>Sắp xếp</h5>
                    <form method="GET" action="{{ route('category.show', $category->slug) }}">
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
            </div>
            <div class="col-md-9">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @foreach ($products as $product)
                    <div class="col">
                        <div class="card h-100">
                            <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                                @if ($product->image_url)
                                <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}" />
                                @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    No Image
                                </div>
                                @endif
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
