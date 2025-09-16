@extends('user.layouts.app')

@section('title', 'Trang chủ - MangaBook')

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
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">📚 Chào mừng đến với ShopManga</h4>
        <p>Khám phá hàng ngàn đầu sách từ văn học, kinh tế, công nghệ đến manga.</p>
        <a href="{{ route('user.products.list') }}" class="btn btn-primary">Xem sản phẩm</a>
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
