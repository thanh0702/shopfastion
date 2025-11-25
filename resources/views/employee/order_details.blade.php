@extends('admin.layout')

@section('title', 'Chi tiết đơn hàng #' . $order->_id)

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Chi tiết đơn hàng #{{ $order->_id }}</h3>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <p><strong>Mã đơn hàng:</strong> #{{ $order->_id }}</p>
                    <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Trạng thái:</strong>
                        <span class="badge
                            @if($order->status == 'pending') bg-warning text-dark
                            @elseif($order->status == 'completed') bg-success
                            @elseif($order->status == 'cancelled') bg-danger
                            @else bg-secondary
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <p><strong>Phương thức thanh toán:</strong>
                        @if($order->payment_method === 'transfer')
                            <span class="badge bg-info">Chuyển khoản</span>
                        @else
                            <span class="badge bg-success">Tiền mặt</span>
                        @endif
                    </p>
                    <p><strong>Tổng tiền:</strong> <span class="text-danger fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }} VND</span></p>
                    <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>

        @if($order->payment_method === 'transfer')
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Thông tin chuyển khoản</h5>
                </div>
                <div class="card-body text-center">
                    @php
                        $qrContent = 'DH' . $order->_id;
                        $amount = intval($order->total_amount);
                        $bankCode = 'BIDV';
                        $accountNumber = '2601663447';
                        $qrUrl = "https://img.vietqr.io/image/{$bankCode}-{$accountNumber}-print.png?amount={$amount}&addInfo=" . urlencode($qrContent);
                    @endphp
                    <img src="{{ $qrUrl }}" alt="VietQR" class="img-fluid mb-3" style="max-width: 300px;">
                    <div class="alert alert-info">
                        <p class="mb-1"><strong>Ngân hàng:</strong> BIDV</p>
                        <p class="mb-1"><strong>Số tài khoản:</strong> {{ $accountNumber }}</p>
                        <p class="mb-1"><strong>Số tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} VND</p>
                        <p class="mb-0"><strong>Nội dung:</strong> {{ $qrContent }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Sản phẩm trong đơn hàng</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Ảnh</th>
                        <th>Size</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
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
                        <td>{{ $item->size ?? 'N/A' }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VND</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Tổng cộng:</th>
                        <th>{{ number_format($order->total_amount, 0, ',', '.') }} VND</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="mb-4">
        <a href="{{ route('employee.sales') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Quay lại trang bán hàng
        </a>
        <a href="{{ route('employee.cart') }}" class="btn btn-secondary">
            <i class="bi bi-cart"></i> Xem giỏ hàng
        </a>

        @if($order->status == 'pending')
        <div class="d-inline-block ms-3">
            <form action="{{ route('employee.order.complete', $order->_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn hoàn thành đơn hàng này? Số lượng sản phẩm sẽ được trừ khỏi kho.')">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Hoàn thành đơn hàng
                </button>
            </form>

            <form action="{{ route('employee.order.cancel', $order->_id) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-x-circle"></i> Hủy đơn hàng
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
