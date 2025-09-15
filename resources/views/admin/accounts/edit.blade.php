@extends('layouts.admin')

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

    <form action="{{ route('accounts.update', $account->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- Cột trái: các trường yêu cầu --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Họ tên *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $account->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" disabled value="{{ old('email', $account->email) }}">
                </div>

                <div class="mb-3 form-password-toggle">
                    <label class="form-label">Mật khẩu (bỏ trống nếu không đổi)</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••">
                </div>

                <div class="mb-3">
                    <label class="form-label">Quyền</label>
                    <select name="role" class="form-select">
                        <option value="0" {{ old('role', $account->role) == 0 ? 'selected' : '' }}>Admin</option>
                        <option value="1" {{ old('role', $account->role) == 1 ? 'selected' : '' }}>Cộng tác viên</option>
                        <!-- <option value="2" {{ old('role', $account->role) == 2 ? 'selected' : '' }}>Khách hàng</option> -->
                    </select>
                </div>
                <div class="mb-3 text-center">
                    <label class="form-label">Ảnh đại diện</label>
                    <img 
                        id="preview-avatar" 
                        src="{{ $account->image ? asset('storage/' . $account->image) : asset('assets/img/avatars/1.png') }}" 
                        alt="avatar" 
                        class="rounded-circle border mb-2" 
                        width="200" 
                        height="200"
                    >
                    <input type="file" class="form-control mt-2" id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Cột phải: thông tin khác --}}
            <div class="col-md-6">
                {{-- Avatar --}}
                

                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $account->address) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $account->phone) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="birth" class="form-control" value="{{ old('birth', $account->birth) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Giới tính</label>
                    <select name="gender" class="form-select">
                        <option value="">-- Chọn --</option>
                        <option value="Male" {{ old('gender', $account->gender) == 'Male' ? 'selected' : '' }}>Nam</option>
                        <option value="Female" {{ old('gender', $account->gender) == 'Female' ? 'selected' : '' }}>Nữ</option>
                        <option value="Other" {{ old('gender', $account->gender) == 'Other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ old('is_active', $account->is_active) == 1 ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ old('is_active', $account->is_active) == 0 ? 'selected' : '' }}>Khoá</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <button type="reset" class="btn btn-warning">Reset</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>

{{-- JS preview ảnh --}}
<script>
document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = function(e){
            document.getElementById('preview-avatar').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
