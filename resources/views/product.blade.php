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
                <img src="{{ $product->images[0] }}" class="img-fluid" alt="{{ $product->name }}" />
                @elseif ($product->image_url)
                <img src="{{ $product->image_url }}" class="img-fluid" alt="{{ $product->name }}" />
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

                <div class="input-group mb-3" style="width: 120px;">
                    <button class="btn btn-dark" type="button" id="decreaseQty">-</button>
                    <input type="number" id="quantity" class="form-control text-center" value="1" min="1" max="{{ $product->stock_quantity }}" />
                    <button class="btn btn-dark" type="button" id="increaseQty">+</button>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}" />
                    <input type="hidden" name="quantity" id="hiddenQuantity" value="1" />
                    <button type="submit" class="btn btn-dark">Thêm vào giỏ hàng</button>
                </form>

                <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}" />
                    <input type="hidden" name="quantity" id="hiddenQuantityBuy" value="1" />
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
    </script>
     @include('partials.footer')
</body>
</html>
