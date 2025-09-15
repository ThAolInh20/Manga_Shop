@extends('user.layouts.app')

@section('title', 'Đăng nhập Admin')

@section('content')
@section('content')
 <!-- Alerts nổi lên dưới navbar -->
  <div id="alerts-container" 
       class="position-fixed top-0 end-0 mt-5 me-3"
       style="z-index: 2000; max-width: 400px;">
    @if (session('status'))
      <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
  
</div>
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="col-md-6 col-lg-4 mx-auto">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-2 text-center">Đăng nhập</h4>

            <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
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

              <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- <a href="{{ route('password.request') }}" class="text-muted small">Quên mật khẩu?</a> -->
                <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-sm">Đăng ký</a>
              </div>

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
 <suggest-products></suggest-products>  
@endsection
