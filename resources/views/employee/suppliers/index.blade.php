@extends('admin.layout')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('employee.sales') }}">Employee Sales</a></li>
        <li class="breadcrumb-item active" aria-current="page">Quản lý nhà cung cấp</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-building"></i> Quản lý nhà cung cấp</h2>
    <a href="{{ route('employee.suppliers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Thêm nhà cung cấp mới
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($suppliers->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Người liên hệ</th>
                            <th>Địa chỉ</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>{{ $supplier->contact_person ?: '-' }}</td>
                                <td>{{ Str::limit($supplier->address, 50) }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('employee.suppliers.edit', $supplier) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </a>
                                        <form action="{{ route('employee.suppliers.destroy', $supplier) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhà cung cấp này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-building display-4 text-muted mb-3"></i>
                <h4 class="text-muted">Không tìm thấy nhà cung cấp nào</h4>
                <p class="text-muted">Bắt đầu bằng cách thêm nhà cung cấp đầu tiên.</p>
                <a href="{{ route('employee.suppliers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Thêm nhà cung cấp đầu tiên
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
