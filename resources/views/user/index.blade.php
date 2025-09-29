@extends('user.layouts.app')

@section('title', 'Trang chủ - MangaShop')

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
<div class="row">
  <div class="col-lg-12 mb-4">
  <div class="card shadow-sm border-0">
    <div class="card-body text-center">
      <h4 class="card-title mb-3">🎉 Chào mừng đến với <strong>MangaShop</strong></h4>
      <p class="text-muted">
        Khám phá <em>thế giới manga bất tận</em> với hàng ngàn tựa truyện hot nhất từ Nhật Bản.  
        Từ những bộ <strong>shounen đầy nhiệt huyết</strong>, <strong>shoujo ngọt ngào</strong> cho đến 
        <strong>seinen sâu lắng</strong> – tất cả đều đang chờ bạn tại MangaShop! 💫
      </p>
      <a href="{{ route('user.products.list') }}" class="btn btn-danger btn-lg rounded-pill">
        📚 Bắt đầu hành trình mua sắm
      </a>
    </div>
  </div>
</div>
      

</div>
 <suggest-products></suggest-products>  
<script>
  setTimeout(() => {
    const alertEl = document.querySelector('#alerts-container .alert');
    if (alertEl) {
      const bsAlert = new bootstrap.Alert(alertEl);
      bsAlert.close();
    }
  }, 3000);
</script>

@endsection
