@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý tin nhắn chat</h3>
                    <a href="{{ route('admin.chat_messages.create') }}" class="btn btn-primary float-right">Thêm tin nhắn</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Người dùng</th>
                                <th>Tin nhắn</th>
                                <th>Loại</th>
                                <th>Session ID</th>
                                <th>Thời gian</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chatMessages as $message)
                            <tr>
                                <td>{{ $message->id }}</td>
                                <td>{{ $message->user ? $message->user->name : 'Khách' }}</td>
                                <td>{{ Str::limit($message->message, 50) }}</td>
                                <td>
                                    <span class="badge {{ $message->is_user ? 'badge-primary' : 'badge-secondary' }}">
                                        {{ $message->is_user ? 'Người dùng' : 'Bot' }}
                                    </span>
                                </td>
                                <td>{{ $message->session_id ?: 'N/A' }}</td>
                                <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.chat_messages.edit', $message) }}" class="btn btn-sm btn-warning">Sửa</a>
                                    <form action="{{ route('admin.chat_messages.delete', $message) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
