@extends('admin.layout')

@section('breadcrumb')
<nav class="breadcrumb" aria-label="breadcrumb">
    <a href="{{ route('employee.sales') }}">Trang bán hàng</a> >
    <a href="{{ route('employee.products.index') }}">Quản lý sản phẩm</a> >
    <span>Chỉnh sửa sản phẩm</span>
</nav>
@endsection

@section('content')
<h2 class="mb-4" style="font-weight: 700; font-size: 20px; color: #1a202c;">Chỉnh sửa sản phẩm</h2>

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

<form action="{{ route('employee.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="category_id" class="form-label">Danh mục</label>
        <select name="category_id" id="category_id" class="form-select" required>
            <option value="">Chọn danh mục</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="name" class="form-label">Tên sản phẩm</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required />
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Mô tả</label>
        <textarea name="description" id="description" class="form-control">{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Giá (VND)</label>
        <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $product->price) }}" min="0" step="0.01" required />
    </div>

    <div class="mb-3">
        <label for="stock_quantity" class="form-label">Số lượng tồn kho</label>
        <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" required />
    </div>

    <div class="mb-3">
        <label for="size" class="form-label">Kích thước (tùy chọn)</label>
        <input type="text" name="size" id="size" class="form-control" value="{{ old('size', $product->size) }}" placeholder="e.g., S, M, L, XL" />
    </div>

    <div class="mb-3">
        <label class="form-label">Hình ảnh sản phẩm (tối đa 5)</label>
        @if($product->images && is_array($product->images))
            @foreach($product->images as $index => $image)
                <div class="mb-2">
                    <img src="{{ $image }}" alt="{{ $product->name }}" style="max-width: 100px; max-height: 100px; margin-right: 10px;">
                    <input type="file" name="images[{{ $index }}]" class="form-control d-inline-block" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" style="width: auto;" />
                </div>
            @endforeach
        @endif
        @for($i = count($product->images ?? []); $i < 5; $i++)
            <input type="file" name="images[{{ $i }}]" class="form-control mb-2" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" />
        @endfor
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
    <a href="{{ route('employee.products.index') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection
