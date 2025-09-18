@extends('admin.layout')

@section('breadcrumb')
<nav class="breadcrumb" aria-label="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> >
    <span>New Banner</span>
</nav>
@endsection

@section('content')
<h2 class="mb-4" style="font-weight: 700; font-size: 20px; color: #1a202c;">Banner information</h2>

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

<form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row mb-4">
        <label for="image" class="col-sm-2 col-form-label form-label">Image <span style="color: red;">*</span></label>
        <div class="col-sm-10">
            <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" required>
        </div>
    </div>
    <div class="row mb-4">
        <label for="link" class="col-sm-2 col-form-label form-label">Link</label>
        <div class="col-sm-10">
            <input type="url" class="form-control" id="link" name="link" value="{{ old('link') }}" placeholder="https://example.com">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
@endsection
