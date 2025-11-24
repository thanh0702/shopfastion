<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Order #{{ $order->id }} - Shop Thời Trang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <style>
        .sidebar {
            min-width: 200px;
            padding-top: 20px;
        }
        .sidebar h2 {
            font-weight: bold;
            padding-left: 15px;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .sidebar a {
            display: block;
            padding: 10px 15px;
            font-weight: bold;
            color: #000;
            text-decoration: none;
            text-transform: uppercase;
            position: relative;
        }
        .sidebar a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: black;
            transition: width 0.3s ease;
        }
        .sidebar a:hover::after {
            width: 100%;
        }
        .content {
            padding: 20px;
        }
        .order-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }
        .status-badge {
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="container my-4 d-flex">
        <nav class="sidebar d-flex flex-column">
            <h2>MY ACCOUNT</h2>
            <div class="d-flex flex-column mb-3">
                <a href="{{ route('account') }}" class="mb-2">DASHBOARD</a>
                <a href="{{ route('account.orders') }}" class="mb-2">ORDERS</a>
                <a href="{{ route('account.details') }}" class="mb-2">ACCOUNT DETAILS</a>
                <a href="{{ route('account.addresses') }}" class="mb-2">ADDRESSES</a>
            </div>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                LOGOUT
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </nav>

        <div class="content flex-grow-1 d-flex flex-column ps-4">
            <h3 class="mb-4">Order Details #{{ $order->id }}</h3>

            <div class="order-info">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Order Information</h5>
                        <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                        <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Status:</strong>
                            <span class="badge status-badge
                                @if($order->status == 'pending') bg-warning text-dark
                                @elseif($order->status == 'completed') bg-success
                                @elseif($order->status == 'cancelled') bg-danger
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                        <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                        <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>

                        @if($order->payment_method === 'transfer')
                        <div class="my-4 text-center">
                            <h5>Thông tin chuyển khoản</h5>
                            @php
                                $qrContent = 'Mã đơn hàng: ' . $order->id;
                                $amount = intval($order->total_amount);
                                $bankCode = 'BIDV';
                                $accountNumber = '2601663447';
                                $qrUrl = "https://img.vietqr.io/image/{$bankCode}-{$accountNumber}.png?amount={$amount}&addInfo=" . urlencode($qrContent);
                            @endphp
                            <img src="{{ $qrUrl }}" alt="VietQR" style="max-width: 200px;">
                            <p><strong>Nội dung chuyển khoản:</strong> {{ $qrContent }} - Số tiền: {{ number_format($order->total_amount, 0, ',', '.') }} VND</p>
                        </div>

                        <form action="{{ route('account.order.saveReceipt', $order) }}" method="POST" enctype="multipart/form-data" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label for="receipt_image" class="form-label">Tải lên biên lai thanh toán</label>
                                <input type="file" class="form-control" id="receipt_image" name="receipt_image" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Gửi biên lai</button>
                        </form>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h5>Shipping Address</h5>
                        <p>{{ $order->shipping_address }}</p>
                    </div>
                </div>
            </div>

            <h4 class="mb-3">Products</h4>
            @foreach($order->orderItems as $item)
                <div class="product-card">
                    @if ($item->product->images && is_array($item->product->images) && count($item->product->images) > 0)
                    <img src="{{ $item->product->images[0] }}" alt="{{ $item->product->name }}" class="product-image">
                    @elseif ($item->product->image_url)
                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="product-image">
                    @else
                    <div class="product-image bg-light d-flex align-items-center justify-content-center">
                        No Image
                    </div>
                    @endif
                    <div class="flex-grow-1">
                        <h6>{{ $item->product->name }}</h6>
                        <p class="mb-1">{{ $item->product->description }}</p>
                        @if($item->size)
                            <p class="mb-0"><strong>Size:</strong> {{ $item->size }}</p>
                        @endif
                        <p class="mb-0"><strong>Quantity:</strong> {{ $item->quantity }}</p>
                        <p class="mb-0"><strong>Price:</strong> ${{ number_format($item->price, 2) }}</p>
                        <p class="mb-0"><strong>Subtotal:</strong> ${{ number_format($item->price * $item->quantity, 2) }}</p>
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('account.orders') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Orders
                </a>
                @if($order->status == 'pending')
                    <form method="POST" action="{{ route('account.order.cancel', $order) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">
                            <i class="bi bi-x-circle"></i> Cancel Order
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.footer')
</body>
</html>
