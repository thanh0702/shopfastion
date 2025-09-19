<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giỏ hàng - Shop Thời Trang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
</head>
<body>
    @include('partials.header')

    <div class="container my-5">
        <h1 class="text-center mb-4">Giỏ hàng của bạn</h1>
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($cartItems->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cartItems as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if ($item->product->image_url)
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 80px; height: 80px;">
                                @endif
                                <div>
                                    <h6 class="mb-0"><a href="{{ route('product.show', $item->product->slug) }}" class="text-decoration-none">{{ $item->product->name }}</a></h6>
                                </div>
                            </div>
                        </td>
                        <td class="item-price" data-price="{{ $item->product->price }}">{{ number_format($item->product->price, 0, ',', '.') }} VND</td>
                        <td>
                            <div class="input-group" style="width: 120px;">
                                <button class="btn btn-outline-secondary btn-sm quantity-btn" type="button" data-cart-item-id="{{ $item->id }}" data-delta="-1">-</button>
                                <input type="number" class="form-control text-center quantity-input" id="quantity-{{ $item->id }}" value="{{ $item->quantity }}" min="1" data-cart-item-id="{{ $item->id }}">
                                <button class="btn btn-outline-secondary btn-sm quantity-btn" type="button" data-cart-item-id="{{ $item->id }}" data-delta="1">+</button>
                            </div>
                        </td>
                        <td class="item-total" data-item-id="{{ $item->id }}">{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }} VND</td>
                        <td>
                            <form action="{{ route('cart.delete') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @php $total += $item->product->price * $item->quantity; @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                        <td class="fw-bold" id="cart-total">{{ number_format($total, 0, ',', '.') }} VND</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="text-end">
            <a href="{{ route('home') }}" class="btn btn-secondary me-2">Tiếp tục mua sắm</a>
            <a href="{{ route('checkout') }}" class="btn btn-success btn-lg">
                <i class="bi bi-credit-card"></i> Thanh toán
            </a>
        </div>
        @else
        <div class="text-center">
            <p>Giỏ hàng của bạn đang trống.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Event delegation for quantity buttons
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('quantity-btn')) {
                const cartItemId = e.target.getAttribute('data-cart-item-id');
                const delta = parseInt(e.target.getAttribute('data-delta'));
                changeQuantity(cartItemId, delta);
            }
        });

        // Event delegation for quantity input changes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('quantity-input')) {
                const cartItemId = e.target.getAttribute('data-cart-item-id');
                const quantity = parseInt(e.target.value);
                updateQuantity(cartItemId, quantity);
            }
        });

        function changeQuantity(cartItemId, delta) {
            const input = document.getElementById('quantity-' + cartItemId);
            let newQuantity = parseInt(input.value) + delta;
            if (newQuantity < 1) newQuantity = 1;
            input.value = newQuantity;
            updateQuantity(cartItemId, newQuantity);
        }

        function updateQuantity(cartItemId, quantity) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    cart_item_id: cartItemId,
                    quantity: quantity
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateTotals(cartItemId, data.item_total, data.cart_total);
                } else {
                    alert('Có lỗi xảy ra khi cập nhật số lượng. Vui lòng thử lại.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật số lượng. Vui lòng thử lại.');
            });
        }

        function updateTotals(updatedItemId, newItemTotal, newCartTotal) {
            // Update the specific item total
            const itemTotalCell = document.querySelector(`.item-total[data-item-id="${updatedItemId}"]`);
            itemTotalCell.textContent = new Intl.NumberFormat('vi-VN').format(newItemTotal) + ' VND';

            // Update overall total
            document.getElementById('cart-total').textContent = new Intl.NumberFormat('vi-VN').format(newCartTotal) + ' VND';
        }

        function checkout() {
            alert('Chức năng thanh toán sẽ được triển khai sau. Hiện tại, vui lòng liên hệ với chúng tôi để đặt hàng.');
        }
    </script>
     @include('partials.footer')
</body>
</html>
