@extends('layouts.auth')

@section('title', 'Đăng nhập Admin')

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">

     
      <div class="col-md-6 col-lg-4 mx-auto">
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-4">
              <a href="{{ route('admin.login') }}" class="app-brand-link gap-2">
                
                <span class="app-brand-text demo text-body fw-bolder">Admin</span>
              </a>
            </div>

            <h4 class="mb-2">Chào mừng quay lại 👋</h4>
            <p class="mb-4">Vui lòng đăng nhập để tiếp tục</p>

            <form id="formAuthentication" class="mb-3" action="{{ route('admin.login') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" placeholder="Nhập email"
                       value="{{ old('email') }}" required autofocus>
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

              @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
              @endif

              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Đăng nhập</button>
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
