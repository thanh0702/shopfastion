@extends('admin.layout')

@section('breadcrumb')
<nav class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> /
    <span>Employees</span>
</nav>
@endsection

@section('content')
<h1>Employees</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($employees as $employee)
            <tr>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->mobile }}</td>
                <td>
                    <!-- Add actions if needed like edit, delete -->
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No employees found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
