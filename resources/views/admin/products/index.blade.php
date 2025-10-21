@extends('admin.layout')

@section('breadcrumb')
<nav class="breadcrumb" aria-label="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> >
    <span>Products</span>
</nav>
@endsection

@section('content')
<h2 class="mb-4" style="font-weight: 700; font-size: 20px; color: #1a202c;">Danh sách sản phẩm</h2>

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

<a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Thêm sản phẩm mới</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>Số lượng tồn kho</th>
            <th>Hình ảnh</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category->name ?? 'N/A' }}</td>
            <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
            <td>{{ $product->stock_quantity }}</td>
            <td>
                @if($product->images && is_array($product->images) && count($product->images) > 0)
                    @foreach($product->images as $index => $image)
                        <img src="{{ $image }}" alt="{{ $product->name }}" style="max-width: 50px; max-height: 50px; margin-right: 5px; margin-bottom: 5px;">
                        @if($index >= 4) @break @endif
                    @endforeach
                @elseif($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="max-width: 100px; max-height: 100px;">
                @else
                    No image
                @endif
            </td>
            <td>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form action="{{ route('admin.products.delete', $product) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
