@extends('admin.layout')

@section('breadcrumb')
<nav class="breadcrumb" aria-label="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> >
    <span>QR Codes</span>
</nav>
@endsection

@section('content')
<h2 class="mb-4" style="font-weight: 700; font-size: 20px; color: #1a202c;">Danh sách QR Code</h2>

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

<a href="{{ route('admin.qr_codes.create') }}" class="btn btn-primary mb-3">Thêm QR Code mới</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Hình ảnh</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($qrCodes as $qrCode)
        <tr>
            <td>{{ $qrCode->id }}</td>
            <td>{{ $qrCode->title ?? 'No title' }}</td>
            <td>{{ Str::limit($qrCode->content, 50) }}</td>
            <td>
                @if($qrCode->image)
                    <img src="{{ $qrCode->image }}" alt="QR Code" style="max-width: 100px; max-height: 100px;">
                @else
                    No image
                @endif
            </td>
            <td>
                <a href="{{ route('admin.qr_codes.edit', $qrCode) }}" class="btn btn-sm btn-warning" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form action="{{ route('admin.qr_codes.delete', $qrCode) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this QR code?');">
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
