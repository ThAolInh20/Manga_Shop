@extends('layouts.admin')

@section('title', 'Chi tiết tài khoản')

@section('content')
<div class="container mt-4">
    <h2>Chi tiết tài khoản</h2>
<div class="mb-3">
    <label for="image" class="form-label">Ảnh đại diện</label>
    <input type="file" class="form-control" id="image" name="image">
    @if(isset($account) && $account->image)
        <img src="{{ asset('storage/' . $account->image) }}" alt="avatar" class="mt-2 rounded-circle" width="80">
    @endif
</div>
    <div class="card p-3">
        <div class="mb-3">
            <strong>Họ tên:</strong> {{ $account->name }}
        </div>

        <div class="mb-3">
            <strong>Email:</strong> {{ $account->email }}
        </div>

        <div class="mb-3">
            <strong>Quyền:</strong> 
            @if($account->role == 0)
                <span class="badge bg-primary">Admin</span>
            @elseif($account->role == 1)
                <span class="badge bg-secondary">Cộng tác viên</span>
            @else
                <span class="badge bg-secondary">Khách hàng</span>
            @endif

        </div>

        <div class="mb-3">
            <strong>Địa chỉ:</strong> {{ $account->address ?? 'Chưa có' }}
        </div>

        <div class="mb-3">
            <strong>Số điện thoại:</strong> {{ $account->phone ?? 'Chưa có' }}
        </div>

        <div class="mb-3">
            <strong>Ngày sinh:</strong> {{ $account->birth ? \Carbon\Carbon::parse($account->birth)->format('d/m/Y') : 'Chưa có' }}
        </div>

        <div class="mb-3">
            <strong>Giới tính:</strong> {{ $account->gender ?? 'Chưa có' }}
        </div>

        <div class="mb-3">
            <strong>Lần đăng nhập cuối:</strong> 
            {{ $account->last_login ? \Carbon\Carbon::parse($account->last_login)->format('d/m/Y H:i') : 'Chưa đăng nhập' }}
        </div>

        <div class="mb-3">
            <strong>Trạng thái:</strong> 
            @if($account->is_active)
                <span class="badge bg-success">Hoạt động</span>
            @else
                <span class="badge bg-danger">Khoá</span>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Quay lại</a>
        <a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-warning">Sửa</a>

        <form action="{{ route('accounts.destroy', $account->id) }}" method="POST" style="display:inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xoá tài khoản này không?')">
                Xoá
            </button>
        </form>
    </div>
</div>
@endsection
