@extends('user.layouts.app')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="col-md-6 col-lg-4 mx-auto">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-2 text-center">Quên mật khẩu</h4>
            <p class="text-muted small text-center">Nhập email để nhận link đặt lại mật khẩu</p>

            @if (session('status'))
              <div class="alert alert-success small">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <button type="submit" class="btn btn-primary d-grid w-100">
                <i class="bx bx-mail-send me-1"></i> Gửi link đặt lại
              </button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
