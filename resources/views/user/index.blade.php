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

<!-- Banner -->
<div class="row mb-4">
  <!-- Cột trái: Banner chính -->
  <div class="col-lg-8 mb-3 mb-lg-0">
    <div class="card shadow-sm border-0 h-100">
      <img src="{{ asset('storage/banner/main_banner.png') }}" 
           alt="Main Banner" 
           class="img-fluid rounded h-100 w-100"
           style="object-fit: cover;">
    </div>
  </div>

  <!-- Cột phải: 2 banner phụ -->
  <div class="col-lg-4 d-flex flex-column gap-3">
    <div class="card shadow-sm border-0 flex-fill">
      <img src="{{ asset('storage/banner/sub/sub_banner_0.png') }}" 
           alt="Sub Banner 1" 
           class="img-fluid rounded h-100 w-100"
           style="object-fit: contain;">
    </div>
    <div id="subBannerCarousel" class="carousel slide card shadow-sm border-0 flex-fill" data-bs-ride="carousel">
  <div class="carousel-inner">
@php
  $subBanners = json_decode($websiteConfig->sub_banners ?? '[]', true);
@endphp
    @foreach ($subBanners as $i => $sb)
  <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
    <img src="{{ asset('storage/' . $sb) }}" 
         alt="Sub Banner {{ $i + 1 }}" 
         class="d-block w-100 rounded"
         style="height: 200px; object-fit: cover; object-position: center;">
  </div>
@endforeach
  </div>

  <!-- Nút mũi tên -->
  <button class="carousel-control-prev" type="button" data-bs-target="#subBannerCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Trước</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#subBannerCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Sau</span>
  </button>
</div>
  </div>
</div>


<!-- Sản phẩm gợi ý -->
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
