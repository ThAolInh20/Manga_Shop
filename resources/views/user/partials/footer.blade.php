<footer class="bg-white text-dark pt-5 pb-3 border-top">
  <div class="container">
    <div class="row gy-4">

      <!-- Giới thiệu -->
      <div class="col-md-3 text-center text-md-start">
        <h5 class="fw-bold mb-3">Về chúng tôi</h5>
        <p class="small">
          MangaShop là nơi cung cấp sách chất lượng, đa dạng thể loại với giá cả hợp lý. 
          Chúng tôi cam kết giao hàng nhanh và hỗ trợ khách hàng tận tâm.
        </p>

        @if(isset($websiteConfig))
          <ul class="list-unstyled small text-muted">
            <li><i class="bi bi-geo-alt-fill me-2"></i>{{ $websiteConfig->address }}</li>
            <li><i class="bi bi-telephone-fill me-2"></i>{{ $websiteConfig->hotline }}</li>
            <li><i class="bi bi-envelope-fill me-2"></i>{{ $websiteConfig->email }}</li>
          </ul>
        @endif
      </div>

      <!-- Liên kết nhanh -->
      <div class="col-md-3 text-center text-md-start">
        <h5 class="fw-bold mb-3">Liên kết nhanh</h5>
        <ul class="list-unstyled">
          <li><a href="{{ url('/') }}" class="text-dark text-decoration-none d-block mb-2">Trang chủ</a></li>
          <li><a href="{{ route('user.products.list') }}" class="text-dark text-decoration-none d-block mb-2">Cửa hàng</a></li>
        </ul>
      </div>

      <!-- Chính sách -->
      <div class="col-md-3 text-center text-md-start">
        <h5 class="fw-bold mb-3">Chính sách chung</h5>
        <ul class="list-unstyled">
          <li><a href="{{ route('policy.terms') }}" class="text-dark text-decoration-none d-block mb-2">Điều khoản sử dụng</a></li>
          <li><a href="{{ route('policy.payment') }}" class="text-dark text-decoration-none d-block mb-2">Chính sách thanh toán</a></li>
          <li><a href="{{ route('policy.privacy') }}" class="text-dark text-decoration-none d-block mb-2">Chính sách bảo mật</a></li>
          <li><a href="{{ route('policy.return') }}" class="text-dark text-decoration-none d-block mb-2">Chính sách đổi – trả</a></li>
        </ul>
      </div>

      <!-- Mạng xã hội -->
      <div class="col-md-3 text-center text-md-start">
        <h5 class="fw-bold mb-3">Kết nối với chúng tôi</h5>
        <div class="d-flex justify-content-center justify-content-md-start gap-3">
          <a href="https://facebook.com" class="text-dark fs-5"><i class="bi bi-facebook"></i></a>
          <a href="https://twitter.com" class="text-dark fs-5"><i class="bi bi-twitter"></i></a>
          <a href="https://instagram.com" class="text-dark fs-5"><i class="bi bi-instagram"></i></a>
        </div>
      </div>

    </div>

    <hr class="my-4">

    <!-- Bản quyền -->
    <div class="text-center small text-muted">
      © {{ date('Y') }} <span class="fw-bold text-dark">MangaShop</span>. All rights reserved.
    </div>
  </div>
</footer>

<style>
  footer a:hover {
    color: #0d6efd !important; /* xanh Bootstrap khi hover */
  }
</style>
