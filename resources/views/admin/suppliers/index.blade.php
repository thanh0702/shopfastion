@extends('admin.layout')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Suppliers</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-building"></i> Supplier Management</h2>
    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Supplier
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Contact Person</th>
                            <th>Address</th>
                            <th>Actions</th>
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
                                        <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.suppliers.destroy', $supplier) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Delete
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
                <h4 class="text-muted">No Suppliers Found</h4>
                <p class="text-muted">Start by adding your first supplier.</p>
                <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add First Supplier
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
