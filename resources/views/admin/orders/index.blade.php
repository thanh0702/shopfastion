@extends('admin.layout')

@section('breadcrumb')
<nav class="breadcrumb" aria-label="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> >
    <span>Orders</span>
</nav>
@endsection

@section('content')
<h2 class="mb-4" style="font-weight: 700; font-size: 20px; color: #1a202c;">Danh sách đơn hàng</h2>

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

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Khách hàng</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Phương thức thanh toán</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->user->name ?? 'N/A' }}</td>
            <td>${{ number_format($order->total_amount, 2) }}</td>
            <td>
                <span class="badge
                    @if($order->status == 'pending') bg-warning text-dark
                    @elseif($order->status == 'completed') bg-success
                    @elseif($order->status == 'cancelled') bg-danger
                    @else bg-secondary
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </td>
            <td>{{ ucfirst($order->payment_method) }}</td>
            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            <td>
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary" title="View">
                    <i class="bi bi-eye"></i> Xem
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
