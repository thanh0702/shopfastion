<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $product->name }} - Shop Thời Trang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <style>
        .btn-dark {
            background-color: #000;
            border-color: #000;
            color: #fff;
        }
        .btn-dark:hover {
            background-color: #333;
            border-color: #333;
        }
        #quantity {
            border: 1px solid #000;
            color: #000;
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="container my-5">
        <div class="row">
            <div class="col-md-6">
                @if ($product->images && is_array($product->images) && count($product->images) > 0)
                    <div class="product-gallery d-flex">
                        <div class="thumbnail-gallery me-3 d-flex flex-column">
                            @foreach($product->images as $index => $image)
                                <img src="{{ $image }}" class="thumbnail mb-2" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid #ddd;" onclick="changeMainImage('{{ $image }}')" />
                            @endforeach
                        </div>
                        <div class="main-image-container">
                            <img id="main-image" src="{{ $product->images[0] }}" class="img-fluid main-image" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;" />
                        </div>
                    </div>
                @elseif ($product->image_url)
                    <img src="{{ $product->image_url }}" class="img-fluid" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;" />
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                        No Image
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <h1>{{ $product->name }}</h1>
                <p class="text-muted">{{ $product->description }}</p>
                <h3 class="text-danger">{{ number_format($product->price, 0, ',', '.') }} VND</h3>
                <p>Số lượng còn lại: {{ $product->stock_quantity }}</p>

                @if($product->size)
                    <div class="mb-3">
                        <label class="form-label">Kích thước:</label>
                        <div class="d-flex flex-wrap">
                            @foreach(explode(',', $product->size) as $sizeOption)
                                <button type="button" class="btn btn-outline-secondary me-2 mb-2 size-btn" data-size="{{ trim($sizeOption) }}">{{ trim($sizeOption) }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="selected_size" id="selected_size" />
                    </div>
                @endif

                <div class="input-group mb-3" style="width: 120px;">
                    <button class="btn btn-dark" type="button" id="decreaseQty">-</button>
                    <input type="number" id="quantity" class="form-control text-center" value="1" min="1" max="{{ $product->stock_quantity }}" />
                    <button class="btn btn-dark" type="button" id="increaseQty">+</button>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}" />
                    <input type="hidden" name="quantity" id="hiddenQuantity" value="1" />
                    <input type="hidden" name="size" id="hiddenSize" value="" />
                    <button type="submit" class="btn btn-dark">Thêm vào giỏ hàng</button>
                </form>

                <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}" />
                    <input type="hidden" name="quantity" id="hiddenQuantityBuy" value="1" />
                    <input type="hidden" name="size" id="hiddenSizeBuy" value="" />
                    <input type="hidden" name="action" value="buy" />
                    <button type="submit" class="btn btn-dark ms-2">Thanh toán</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const decreaseBtn = document.getElementById('decreaseQty');
        const increaseBtn = document.getElementById('increaseQty');
        const quantityInput = document.getElementById('quantity');
        const hiddenQuantity = document.getElementById('hiddenQuantity');
        const hiddenQuantityBuy = document.getElementById('hiddenQuantityBuy');

        decreaseBtn.addEventListener('click', () => {
            let qty = parseInt(quantityInput.value);
            if (qty > 1) {
                qty--;
                quantityInput.value = qty;
                hiddenQuantity.value = qty;
                hiddenQuantityBuy.value = qty;
            }
        });

        increaseBtn.addEventListener('click', () => {
            let qty = parseInt(quantityInput.value);
            const max = parseInt(quantityInput.max);
            if (qty < max) {
                qty++;
                quantityInput.value = qty;
                hiddenQuantity.value = qty;
                hiddenQuantityBuy.value = qty;
            }
        });

        quantityInput.addEventListener('change', () => {
            let qty = parseInt(quantityInput.value);
            const min = parseInt(quantityInput.min);
            const max = parseInt(quantityInput.max);
            if (qty < min) qty = min;
            if (qty > max) qty = max;
            quantityInput.value = qty;
            hiddenQuantity.value = qty;
            hiddenQuantityBuy.value = qty;
        });

        function changeMainImage(imageSrc) {
            document.getElementById('main-image').src = imageSrc;
        }

        // Size selection
        const sizeButtons = document.querySelectorAll('.size-btn');
        const selectedSizeInput = document.getElementById('selected_size');
        const hiddenSize = document.getElementById('hiddenSize');
        const hiddenSizeBuy = document.getElementById('hiddenSizeBuy');

        sizeButtons.forEach(button => {
            button.addEventListener('click', () => {
                sizeButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                const size = button.getAttribute('data-size');
                selectedSizeInput.value = size;
                hiddenSize.value = size;
                hiddenSizeBuy.value = size;
            });
        });
    </script>
     @include('partials.footer')
</body>
</html>
