@extends('admin.layout')

@section('breadcrumb')
<nav class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> /
    <a href="{{ route('admin.products.create') }}">Add Product</a>
</nav>
@endsection

@section('content')
<h1>Add New Product</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="category_id" class="form-label">Category</label>
        <select name="category_id" id="category_id" class="form-select" required>
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="name" class="form-label">Product Name</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required />
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Price (VND)</label>
        <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" min="0" step="0.01" required />
    </div>

    <div class="mb-3">
        <label for="stock_quantity" class="form-label">Stock Quantity</label>
        <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" value="{{ old('stock_quantity') }}" min="0" required />
    </div>

    <div class="mb-3">
        <label class="form-label">Product Images (up to 5, optional)</label>
        <input type="file" name="images[]" class="form-control mb-2" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" />
        <input type="file" name="images[]" class="form-control mb-2" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" />
        <input type="file" name="images[]" class="form-control mb-2" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" />
        <input type="file" name="images[]" class="form-control mb-2" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" />
        <input type="file" name="images[]" class="form-control mb-2" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" />
    </div>

    <button type="submit" class="btn btn-primary">Add Product</button>
</form>
@endsection
