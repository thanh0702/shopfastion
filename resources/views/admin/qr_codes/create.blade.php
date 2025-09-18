@extends('admin.layout')

@section('breadcrumb')
<nav class="breadcrumb" aria-label="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> >
    <a href="{{ route('admin.qr_codes.index') }}">QR Codes</a> >
    <span>New QR Code</span>
</nav>
@endsection

@section('content')
<h2 class="mb-4" style="font-weight: 700; font-size: 20px; color: #1a202c;">Thông tin QR Code</h2>

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

<form action="{{ route('admin.qr_codes.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row mb-4">
        <label for="title" class="col-sm-2 col-form-label form-label">Title</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Enter QR code title">
        </div>
    </div>
    <div class="row mb-4">
        <label for="content" class="col-sm-2 col-form-label form-label">Content <span style="color: red;">*</span></label>
        <div class="col-sm-10">
            <textarea class="form-control" id="content" name="content" rows="4" placeholder="Enter QR code content" required>{{ old('content') }}</textarea>
        </div>
    </div>
    <div class="row mb-4">
        <label for="image" class="col-sm-2 col-form-label form-label">Image</label>
        <div class="col-sm-10">
            <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.qr_codes.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
