@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chi tiết tin nhắn chat</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>ID:</strong></label>
                                <p>{{ $chatMessage->id }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Người dùng:</strong></label>
                                <p>{{ $chatMessage->user ? $chatMessage->user->name : 'Khách' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Loại tin nhắn:</strong></label>
                                <p>
                                    <span class="badge {{ $chatMessage->is_user ? 'badge-primary' : 'badge-secondary' }}">
                                        {{ $chatMessage->is_user ? 'Người dùng' : 'Bot' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Session ID:</strong></label>
                                <p>{{ $chatMessage->session_id ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Thời gian:</strong></label>
                                <p>{{ $chatMessage->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><strong>Tin nhắn:</strong></label>
                        <div class="border p-3 bg-light">
                            {{ $chatMessage->message }}
                        </div>
                    </div>
                    <a href="{{ route('admin.chat_messages.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
