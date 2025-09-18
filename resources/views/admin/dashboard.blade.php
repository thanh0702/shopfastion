@extends('admin.layout')

@section('breadcrumb')
<nav class="breadcrumb" aria-label="breadcrumb">
    <span>Dashboard</span>
</nav>
@endsection

@section('content')
<h2 class="mb-4" style="font-weight: 700; font-size: 20px; color: #1a202c;">Admin Dashboard</h2>
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
                <a href="#" class="btn btn-primary">Quản lý đơn hàng</a>
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
