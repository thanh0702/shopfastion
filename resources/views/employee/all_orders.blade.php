@extends('admin.layout')

@section('title', 'Tất cả đơn hàng')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Danh sách tất cả đơn hàng</h3>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Phương thức thanh toán</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->_id }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
                    <td>
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
                    </td>
                    <td>
                        @if($order->payment_method === 'transfer')
                            <span class="badge bg-info">Chuyển khoản</span>
                        @else
                            <span class="badge bg-success">Tiền mặt</span>
                        @endif
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('employee.orders.show', $order->_id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> Xem chi tiết
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('employee.sales') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại trang bán hàng
        </a>
    </div>
</div>
@endsection
