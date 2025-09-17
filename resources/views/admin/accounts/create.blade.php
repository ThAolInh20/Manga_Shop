@extends('layouts.admin')

@section('title', 'Thêm tài khoản mới')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Thêm tài khoản mới</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('accounts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            {{-- Yêu cầu nhập --}}
            <div class="col-md-6">
                <h5 class="mb-3">Thông tin cơ bản</h5>

                <div class="mb-3">
                    <label for="name" class="form-label">Họ tên *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu *</label>
                    <input type="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Quyền</label>
                    <select name="role" class="form-select">
                        <option value="0" {{ old('role') == 0 ? 'selected' : '' }}>Admin</option>
                        <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>Cộng tác viên</option>
                    </select>
                </div>
            </div>

            {{-- Thông tin khác --}}
            <div class="col-md-6">
                <h5 class="mb-3">Thông tin khác</h5>

                <!-- {{-- Ảnh đại diện --}}
                <div class="mb-3 text-center">
                    <label class="form-label d-block">Ảnh đại diện</label>
                    <img id="previewImage" src="{{ asset('assets/img/avatars/default.png') }}" 
                        alt="avatar" class="rounded-circle border mb-2" width="150" height="150">
                    <input type="file" class="form-control mt-2" id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div> -->

                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>

                <div class="mb-3">
                    <label for="birth" class="form-label">Ngày sinh</label>
                    <input type="date" name="birth" class="form-control" value="{{ old('birth') }}">
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Giới tính</label>
                    <select name="gender" class="form-select">
                        <option value="">-- Chọn --</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Nam</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Nữ</option>
                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                
            </div>
        </div>

        {{-- Buttons --}}
        <div class="mt-4 text-center">
            <button type="submit" class="btn btn-primary me-2">Lưu</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>

{{-- JS preview ảnh --}}
<script>
document.getElementById('image').addEventListener('change', function(e){
    const file = e.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = function(event){
            document.getElementById('previewImage').src = event.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
