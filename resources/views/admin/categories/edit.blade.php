@extends('admin.layout')

@section('breadcrumb')
<nav class="breadcrumb" aria-label="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> >
    <a href="{{ route('admin.categories.index') }}">Categories</a> >
    <span>Edit Category</span>
</nav>
@endsection

@section('content')
<h2 class="mb-4" style="font-weight: 700; font-size: 20px; color: #1a202c;">Edit Category</h2>

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

<form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row mb-4">
        <label for="name" class="col-sm-2 col-form-label form-label">Category Name <span style="color: red;">*</span></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}" required placeholder="Category name">
        </div>
    </div>
    <div class="row mb-4">
        <label for="slug" class="col-sm-2 col-form-label form-label">Category Slug <span style="color: red;">*</span></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" required placeholder="Category Slug">
        </div>
    </div>
    <div class="row mb-4">
        <label for="description" class="col-sm-2 col-form-label form-label">Description</label>
        <div class="col-sm-10">
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Category description">{{ old('description', $category->description) }}</textarea>
        </div>
    </div>
    <div class="row mb-4">
        <label for="image" class="col-sm-2 col-form-label form-label">Image</label>
        <div class="col-sm-10">
            @if($category->imageurl)
                <img src="{{ $category->imageurl }}" alt="{{ $category->name }}" style="max-width: 100px; max-height: 100px; margin-bottom: 10px;">
            @endif
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
</form>

<script>
document.getElementById('name').addEventListener('input', function() {
    var name = this.value;
    var slug = name.toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
    document.getElementById('slug').value = slug;
});
</script>
@endsection
