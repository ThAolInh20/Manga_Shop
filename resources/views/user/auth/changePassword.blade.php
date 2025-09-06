@extends('user.layouts.app')

@section('title', 'Đổi mật khẩu')

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="col-md-6 col-lg-4 mx-auto">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-2 text-center">Đổi mật khẩu</h4>

            <form action="{{ route('password.update') }}" method="POST">
              @csrf

              <!-- Mật khẩu cũ -->
              <div class="mb-3 form-password-toggle">
                <label class="form-label" for="current_password">Mật khẩu hiện tại</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="current_password"
                         class="form-control @error('current_password') is-invalid @enderror"
                         name="current_password" placeholder="••••••••" required>
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  @error('current_password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Mật khẩu mới -->
              <div class="mb-3 form-password-toggle">
                <label class="form-label" for="new_password">Mật khẩu mới</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="new_password"
                         class="form-control @error('new_password') is-invalid @enderror"
                         name="new_password" placeholder="••••••••" required>
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  @error('new_password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Xác nhận mật khẩu mới -->
              <div class="mb-3 form-password-toggle">
                <label class="form-label" for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="new_password_confirmation"
                         class="form-control @error('new_password_confirmation') is-invalid @enderror"
                         name="new_password_confirmation" placeholder="••••••••" required>
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  @error('new_password_confirmation')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">
                  <i class="bx bx-key me-1"></i> Đổi mật khẩu
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
