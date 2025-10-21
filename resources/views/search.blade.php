<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Search - Shop Thời Trang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
</head>
<body>
    @include('partials.header')

    <div class="container my-5">
        <h2 class="text-center mb-4">Tìm kiếm sản phẩm</h2>

        <!-- Search Form -->
        <!-- Removed search form as per user request -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
            </div>
        </div>

        @if($query)
            <p class="text-center">Kết quả tìm kiếm cho: <strong>"{{ $query }}"</strong></p>
        @endif

        <div class="row">
            <div class="col-md-3">
                <div class="card p-3 mb-4">
                    <h5>Sắp xếp</h5>
                    <form method="GET" action="{{ route('search') }}">
                        <input type="hidden" name="q" value="{{ $query }}">
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
                @if($products->count() > 0)
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @foreach ($products as $product)
                        <div class="col">
                            <div class="card h-100">
                                <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                                    @if ($product->images && is_array($product->images) && count($product->images) > 0)
                                    <img src="{{ $product->images[0] }}" class="card-img-top" alt="{{ $product->name }}" />
                                    @elseif ($product->image_url)
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
                @else
                    <div class="text-center">
                        <p>Không tìm thấy sản phẩm nào phù hợp với từ khóa "{{ $query }}".</p>
                        <a href="{{ route('shop') }}" class="btn btn-primary">Xem tất cả sản phẩm</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     @include('partials.footer')
</body>
</html>
