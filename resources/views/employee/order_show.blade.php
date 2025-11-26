@extends('admin.layout')

@section('title', 'Chi tiết đơn hàng')

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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID đơn hàng:</strong> #{{ $order->_id }}</p>
                            <p><strong>Khách hàng:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Trạng thái:</strong>
                                <span class="badge
                                    @if($order->status == 'pending') bg-warning text-dark
                                    @elseif($order->status == 'completed') bg-success
                                    @elseif($order->status == 'cancelled') bg-danger
                                    @else bg-secondary
                                    @endif">
                                    @switch($order->status)
                                        @case('pending')
                                            Chờ xử lý
                                            @break
                                        @case('completed')
                                            Hoàn thành
                                            @break
                                        @case('cancelled')
                                            Đã hủy
                                            @break
                                        @default
                                            {{ ucfirst($order->status) }}
                                    @endswitch
                                </span>
                            </p>
                            <p><strong>Phương thức thanh toán:</strong>
                                @if($order->payment_method === 'transfer')
                                    Chuyển khoản
                                @else
                                    Tiền mặt
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Sản phẩm trong đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Kích thước</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</td>
                                    <td>{{ $item->size ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                                    <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VND</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Tổng cộng:</th>
                                    <th class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }} VND</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            @if($order->payment_method == 'transfer' && $order->receiptQrs->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Biên lai thanh toán</h5>
                </div>
                <div class="card-body">
                    @foreach($order->receiptQrs as $receipt)
                    <div class="mb-3">
                        <img src="{{ $receipt->image }}" alt="Receipt" class="img-fluid">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($order->payment_method === 'transfer')
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Mã QR thanh toán</h5>
                </div>
                <div class="card-body text-center">
                    <img src="https://img.vietqr.io/image/BIDV-2601663447-print.png?amount={{ $order->total_amount }}&addInfo=DH{{ $order->_id }}" alt="QR Code" class="img-fluid">
                    <p class="mt-2"><strong>Số tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} VND</p>
                    <p><strong>Nội dung:</strong> DH{{ $order->_id }}</p>
                </div>
            </div>
            @endif

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Cập nhật trạng thái</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('employee.orders.update', $order) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="active" {{ $order->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Hoàn tiền</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('employee.all-orders') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách đơn hàng
        </a>
    </div>
</div>
@endsection
