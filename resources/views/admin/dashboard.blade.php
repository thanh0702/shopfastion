@extends('admin.layout')

@section('breadcrumb')
<nav class="breadcrumb" aria-label="breadcrumb">
    <span>Dashboard</span>
</nav>
@endsection

@section('content')
<h2 class="mb-4" style="font-weight: 700; font-size: 20px; color: #1a202c;">Admin Dashboard</h2>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Tổng số đơn hàng</h5>
                <h3>{{ $totalOrders }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Tổng doanh thu</h5>
                <h3>{{ number_format($totalRevenue, 0, ',', '.') }} VND</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Đơn hàng đang chờ</h5>
                <h3>{{ $totalPending }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Đơn hàng đang vận chuyển</h5>
                <h3>{{ $totalShipping }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Revenue Chart -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Doanh thu hàng tháng (Đơn hàng đã hoàn thành)</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Management Cards -->
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Quản lý danh mục</h5>
                <p class="card-text">Thêm, sửa, xóa danh mục sản phẩm.</p>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">Xem danh mục</a>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-secondary mt-2">Thêm danh mục</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Quản lý sản phẩm</h5>
                <p class="card-text">Thêm, sửa, xóa sản phẩm.</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Thêm sản phẩm</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Quản lý đơn hàng</h5>
                <p class="card-text">Xem và xử lý đơn hàng.</p>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">Quản lý đơn hàng</a>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Quản lý người dùng</h5>
                <p class="card-text">Xem và quản lý tài khoản người dùng.</p>
                <a href="#" class="btn btn-primary">Quản lý người dùng</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json(array_keys($monthlyRevenue)),
            datasets: [{
                label: 'Doanh thu (VND)',
                data: @json(array_values($monthlyRevenue)),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' VND';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
