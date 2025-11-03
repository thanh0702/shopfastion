@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tin nhắn chat từ khách hàng</h3>
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
                                <th>Chi tiết</th>
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
                                    <a href="{{ route('admin.chat_messages.edit', $message) }}" class="btn btn-sm btn-info">Xem</a>
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
