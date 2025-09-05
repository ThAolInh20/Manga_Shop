@extends('user.layouts.app')

@section('title', 'Đăng ký tài khoản')

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="col-md-6 col-lg-4 mx-auto">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-2 text-center">Đăng ký</h4>

            <form id="formRegister" class="mb-3" action="{{ route('register') }}" method="POST">
              @csrf
              
              <div class="mb-3">
                <label for="name" class="form-label">Họ và tên</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       id="name" name="name" placeholder="Nhập họ và tên"
                       value="{{ old('name') }}" required autofocus>
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" placeholder="Nhập email"
                       value="{{ old('email') }}" required>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 form-password-toggle">
                <label class="form-label" for="password">Mật khẩu</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" 
                         class="form-control @error('password') is-invalid @enderror"
                         name="password" placeholder="••••••••" required>
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="mb-3 form-password-toggle">
                <label class="form-label" for="password_confirmation">Xác nhận mật khẩu</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password_confirmation" 
                         class="form-control @error('password_confirmation') is-invalid @enderror"
                         name="password_confirmation" placeholder="••••••••" required>
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  @error('password_confirmation')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('login') }}" class="text-muted small">Đã có tài khoản? Đăng nhập</a>
              </div>

              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">
                  <i class="bx bx-user-plus me-1"></i> Đăng ký
                </button>
              </div>
            </form>

          </div>
        </div>
      </div>
      <!-- End col -->
    </div>
  </div>
</div>
@endsection
