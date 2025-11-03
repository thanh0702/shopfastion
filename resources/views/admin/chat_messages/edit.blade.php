@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sửa tin nhắn chat</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.chat_messages.update', $chatMessage) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="message">Tin nhắn</label>
                            <textarea class="form-control" id="message" name="message" rows="3" required>{{ old('message', $chatMessage->message) }}</textarea>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_user" name="is_user" value="1" {{ old('is_user', $chatMessage->is_user) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_user">
                                    Là tin nhắn của người dùng
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="session_id">Session ID</label>
                            <input type="text" class="form-control" id="session_id" name="session_id" value="{{ old('session_id', $chatMessage->session_id) }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.chat_messages.index') }}" class="btn btn-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
