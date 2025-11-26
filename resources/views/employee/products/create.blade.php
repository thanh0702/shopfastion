@extends('admin.layout')

@section('breadcrumb')
<nav class="breadcrumb" aria-label="breadcrumb">
    <a href="{{ route('employee.sales') }}">Sales</a> >
    <a href="{{ route('employee.products.index') }}">Products</a> >
    <span>Create Product</span>
</nav>
@endsection

@section('content')
<h2 class="mb-4" style="font-weight: 700; font-size: 20px; color: #1a202c;">Thêm sản phẩm mới</h2>

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

<form action="{{ route('employee.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="category_id" class="form-label">Danh mục</label>
        <select name="category_id" id="category_id" class="form-select" required>
            <option value="">Chọn danh mục</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="name" class="form-label">Tên sản phẩm</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required />
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Mô tả</label>
        <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Giá (VND)</label>
        <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" min="0" step="0.01" required />
    </div>

    <div class="mb-3">
        <label for="stock_quantity" class="form-label">Số lượng tồn kho</label>
        <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" value="{{ old('stock_quantity') }}" min="0" required />
    </div>

    <div class="mb-3">
        <label for="size" class="form-label">Kích thước (tùy chọn)</label>
        <input type="text" name="size" id="size" class="form-control" value="{{ old('size') }}" placeholder="e.g., S, M, L, XL" />
    </div>

    <div class="mb-3">
        <label class="form-label">Hình ảnh sản phẩm (tối đa 5)</label>
        @for($i = 0; $i < 5; $i++)
            <input type="file" name="images[{{ $i }}]" class="form-control mb-2" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" />
        @endfor
    </div>

    <button type="submit" class="btn btn-primary">Tạo sản phẩm</button>
    <a href="{{ route('employee.products.index') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection
