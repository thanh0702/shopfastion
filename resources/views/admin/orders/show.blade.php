@extends('admin.layout')

@section('breadcrumb')
<nav class="breadcrumb" aria-label="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> >
    <a href="{{ route('admin.orders.index') }}">Orders</a> >
    <span>Order #{{ $order->id }}</span>
</nav>
@endsection

@section('content')
<h2 class="mb-4" style="font-weight: 700; font-size: 20px; color: #1a202c;">Chi tiết đơn hàng #{{ $order->id }}</h2>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Thông tin đơn hàng</h5>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> #{{ $order->id }}</p>
                <p><strong>Khách hàng:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Phương thức thanh toán:</strong> {{ ucfirst($order->payment_method) }}</p>
                <p><strong>Tổng tiền:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Sản phẩm</h5>
            </div>
            <div class="card-body">
                @foreach($order->orderItems as $item)
                    <div class="d-flex align-items-center mb-3">
                        @if ($item->product->images && is_array($item->product->images) && count($item->product->images) > 0)
                    <img src="{{ $item->product->images[0] }}" alt="{{ $item->product->name }}" class="product-image">
                    @elseif ($item->product->image_url)
                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="product-image">
                    @else
                    <div class="product-image bg-light d-flex align-items-center justify-content-center">
                        No Image
                    </div>
                    @endif
                        <div>
                            <h6>{{ $item->product->name }}</h6>
                            <p class="mb-0">Số lượng: {{ $item->quantity }} | Giá: ${{ number_format($item->price, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if($order->payment_method == 'transfer' && $order->receiptQrs->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5>Biên lai thanh toán</h5>
            </div>
            <div class="card-body">
                @foreach($order->receiptQrs as $receipt)
                    <img src="{{ $receipt->image }}" alt="Receipt" style="max-width: 100%; height: auto; margin-bottom: 10px;">
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Cập nhật trạng thái</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Shipping</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
</div>
@endsection
