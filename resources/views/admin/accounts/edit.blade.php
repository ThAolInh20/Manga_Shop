@extends('admin.layout')

@section('title', 'Sửa tài khoản')

@section('content')
<div class="container mt-4">
    <h2>Sửa tài khoản</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('accounts.update', $account->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Họ tên</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $account->name) }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email', $account->email) }}">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu (để trống nếu không đổi)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Quyền</label>
            <select name="role" class="form-select">
                <option value="0" {{ old('role', $account->role) == 0 ? 'selected' : '' }}>Admin</option>
                <option value="1" {{ old('role', $account->role) == 1 ? 'selected' : '' }}>Cộng tác viên</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $account->address) }}">
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $account->phone) }}">
        </div>

        <div class="mb-3">
            <label for="birth" class="form-label">Ngày sinh</label>
            <input type="date" name="birth" class="form-control" value="{{ old('birth', $account->birth) }}">
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label">Giới tính</label>
            <select name="gender" class="form-select">
                <option value="">-- Chọn --</option>
                <option value="Male" {{ old('gender', $account->gender) == 'Male' ? 'selected' : '' }}>Nam</option>
                <option value="Female" {{ old('gender', $account->gender) == 'Female' ? 'selected' : '' }}>Nữ</option>
                <option value="Other" {{ old('gender', $account->gender) == 'Other' ? 'selected' : '' }}>Khác</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="is_active" class="form-label">Trạng thái</label>
            <select name="is_active" class="form-select">
                <option value="1" {{ old('is_active', $account->is_active) == 1 ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ old('is_active', $account->is_active) == 0 ? 'selected' : '' }}>Khoá</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <button type="reset" class="btn btn-warning">Reset</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection