@extends('admin.layout')

@section('breadcrumb')
<nav class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> /
    Admin List
</nav>
@endsection

@section('content')
<h2>Admin Users</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <ul>
    @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
    </ul>
</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($admins as $admin)
        <tr>
            <td>{{ $admin->name }}</td>
            <td>{{ $admin->email }}</td>
            <td>{{ $admin->mobile }}</td>
            <td>
                @if(auth()->id() !== $admin->id)
                    <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin?');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                @else
                    <span class="text-muted">Current User</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4">No admin users found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
