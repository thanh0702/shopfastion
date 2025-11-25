@extends('admin.layout')

@section('title', 'Giỏ hàng nhân viên')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Giỏ hàng nhân viên</h3>

    @if($cart->items->isEmpty())
    <div class="alert alert-info">Giỏ hàng đang trống.</div>
    @else
    <table class="table table-bordered align-middle text-center">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Ảnh</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart->items as $item)
            <tr data-cart-item-id="{{ $item->_id }}">
                <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                <td>
                    @if($item->product && $item->product->images && is_array($item->product->images) && count($item->product->images) > 0)
                    <img src="{{ $item->product->images[0] }}" alt="{{ $item->product->name }}" style="max-height: 50px;">
                    @elseif($item->product && $item->product->image_url)
                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" style="max-height: 50px;">
                    @else
                    N/A
                    @endif
                </td>
                <td>{{ number_format($item->product->price ?? 0, 0, ',', '.') }} VND</td>
                <td>
                    <div class="input-group quantity-control" style="width: 120px; margin: auto;">
                        <button class="btn btn-outline-secondary btn-decrease-qty" type="button">-</button>
                        <input type="text" class="form-control text-center qty-input" value="{{ $item->quantity }}" readonly>
                        <button class="btn btn-outline-secondary btn-increase-qty" type="button">+</button>
                    </div>
                </td>
                <td class="item-total">{{ number_format(($item->product->price ?? 0) * $item->quantity, 0, ',', '.') }} VND</td>
                <td>
                    <button class="btn btn-danger btn-sm btn-delete-item">Xóa</button>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Tổng cộng</th>
                <th id="cart-total">
                    {{ number_format($cart->items->reduce(function ($carry, $item) {
                        return $carry + (($item->product->price ?? 0) * $item->quantity);
                    }, 0), 0, ',', '.') }} VND
                </th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    @endif

    <a href="{{ route('employee.sales') }}" class="btn btn-secondary">Quay lại trang bán hàng</a>
</div>

<h5 class="mt-4">Chọn hình thức thanh toán</h5>
<form id="payment-form" class="mb-4">
    <div class="form-check">
        <input class="form-check-input" type="radio" name="payment_method" id="payment_transfer" value="transfer" checked>
        <label class="form-check-label" for="payment_transfer">Chuyển khoản</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash">
        <label class="form-check-label" for="payment_cash">Trả bằng tiền mặt</label>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function updateCartCount(count) {
        var badge = document.getElementById('employee-cart-count');
        if (badge) {
            badge.textContent = count;
        }
    }

    // Update cart item quantity via AJAX
    function updateQuantity(cartItemId, newQuantity, row) {
        fetch("/employee/cart/update", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                cart_item_id: cartItemId,
                quantity: newQuantity
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update quantity input
                row.querySelector('.qty-input').value = newQuantity;

                // Update item total
                const priceText = row.querySelector('td:nth-child(3)').textContent.replace(/[^\d]/g, '');
                const price = parseInt(priceText) || 0;
                row.querySelector('.item-total').textContent = new Intl.NumberFormat('vi-VN').format(price * newQuantity) + ' VND';

                // Update cart total
                recalculateCartTotal();

                // Update cart count badge
                updateCartCount(data.cartCount);
            } else {
                alert('Lỗi cập nhật số lượng: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi cập nhật số lượng sản phẩm.');
        });
    }

    // Delete cart item via AJAX
    function deleteCartItem(cartItemId, row) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) return;

        fetch("/employee/cart/delete", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                cart_item_id: cartItemId
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove row from table
                row.remove();

                // Update cart total
                recalculateCartTotal();

                // Update cart count badge
                updateCartCount(data.cartCount);

                // If cart empty show alert
                if (document.querySelectorAll('tbody tr').length === 0) {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-info';
                    alertDiv.textContent = 'Giỏ hàng đang trống.';
                    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('a.btn-secondary'));
                    document.querySelector('table').remove();
                }
            } else {
                alert('Lỗi xóa sản phẩm: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi xóa sản phẩm khỏi giỏ hàng.');
        });
    }

    // Recalculate total price in cart footer
    function recalculateCartTotal() {
        let total = 0;
        document.querySelectorAll('tbody tr').forEach(row => {
            const qty = parseInt(row.querySelector('.qty-input').value) || 0;
            const priceText = row.querySelector('td:nth-child(3)').textContent.replace(/[^\d]/g, '');
            const price = parseInt(priceText) || 0;
            total += qty * price;
        });
        document.getElementById('cart-total').textContent = new Intl.NumberFormat('vi-VN').format(total) + ' VND';
    }

    // Event delegation for increment and decrement buttons
    document.querySelector('tbody').addEventListener('click', function(e) {
        const target = e.target;
        const row = target.closest('tr');
        if (!row) return;
        const cartItemId = row.getAttribute('data-cart-item-id');
        const qtyInput = row.querySelector('.qty-input');
        let quantity = parseInt(qtyInput.value);

        if (target.classList.contains('btn-increase-qty')) {
            quantity++;
            updateQuantity(cartItemId, quantity, row);
        } else if (target.classList.contains('btn-decrease-qty')) {
            if (quantity > 1) {
                quantity--;
                updateQuantity(cartItemId, quantity, row);
            }
        } else if (target.classList.contains('btn-delete-item')) {
            deleteCartItem(cartItemId, row);
        }
    });
});
</script>

@endsection
