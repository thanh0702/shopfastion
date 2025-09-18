<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Thanh toán - Shop Thời Trang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
</head>
<body>
    @include('partials.header')

    <div class="container my-5">
        <h1 class="text-center mb-4">Thanh toán</h1>
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form action="{{ route('payment.process') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Address Selection -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Chọn địa chỉ giao hàng</h5>
                </div>
                <div class="card-body">
                    @if($addresses->count() > 0)
                    @foreach($addresses as $address)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="address_id" id="address-{{ $address->id }}" value="{{ $address->id }}" {{ $loop->first ? 'checked' : '' }}>
                        <label class="form-check-label" for="address-{{ $address->id }}">
                            <strong>{{ $address->full_name }}</strong> - {{ $address->phone }}<br>
                            {{ $address->number }}, {{ $address->address }}
                        </label>
                    </div>
                    @endforeach
                    @else
                    <p>Bạn chưa có địa chỉ. <a href="{{ route('account.addresses.create') }}">Thêm địa chỉ</a></p>
                    @endif
                </div>
            </div>

            <!-- Product List -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Sản phẩm</h5>
                </div>
                <div class="card-body">
                    @php $total = 0; @endphp
                    @foreach($cartItems as $item)
                    <div class="d-flex align-items-center mb-3">
                        @if ($item->product->image_url)
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 80px; height: 80px;">
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                            <p class="mb-0">Số lượng: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-end">
                            <p class="mb-0 fw-bold">{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }} VND</p>
                        </div>
                    </div>
                    @php $total += $item->product->price * $item->quantity; @endphp
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Tổng cộng:</strong>
                        <strong>{{ number_format($total, 0, ',', '.') }} VND</strong>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Phương thức thanh toán</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash" checked>
                        <label class="form-check-label" for="cash">
                            <strong>Thanh toán bằng tiền mặt</strong>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="transfer" value="transfer">
                        <label class="form-check-label" for="transfer">
                            <strong>Chuyển khoản</strong>
                        </label>
                    </div>
                </div>
            </div>

            <!-- QR Code Section (hidden by default) -->
            <div class="card mb-4" id="qr-section" style="display: none;">
                <div class="card-header">
                    <h5>Thông tin chuyển khoản</h5>
                </div>
                <div class="card-body text-center">
                    @if($qrCode && $qrCode->image)
                    <img src="{{ $qrCode->image }}" alt="QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                    <p><strong>Nội dung:</strong> <span id="qr-content">{{ $qrCode->content }}</span></p>
                    <button type="button" class="btn btn-outline-primary" onclick="copyContent()">Sao chép nội dung</button>
                    @endif
                    <div class="mt-3">
                        <label for="receipt_image" class="form-label">Tải lên biên lai thanh toán</label>
                        <input type="file" class="form-control" id="receipt_image" name="receipt_image" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success btn-lg">Đặt hàng</button>
                <a href="{{ route('cart.show') }}" class="btn btn-secondary btn-lg ms-2">Quay lại giỏ hàng</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show/hide QR section based on payment method
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const qrSection = document.getElementById('qr-section');
                if (this.value === 'transfer') {
                    qrSection.style.display = 'block';
                } else {
                    qrSection.style.display = 'none';
                }
            });
        });

        function copyContent() {
            const content = document.getElementById('qr-content').textContent;
            navigator.clipboard.writeText(content).then(() => {
                alert('Nội dung đã được sao chép!');
            });
        }
    </script>
     @include('partials.footer')
</body>
</html>
