<footer class="bg-white text-dark pt-5 pb-3 border-top">
  <div class="container">
    <div class="row">

      <!-- Giới thiệu -->
      <div class="col-md-3 mb-4">
  <h5 class="fw-bold">Về chúng tôi</h5>
  <p>MangaShop là nơi cung cấp sách chất lượng, đa dạng thể loại với giá cả hợp lý. 
     Chúng tôi cam kết giao hàng nhanh và hỗ trợ khách hàng tận tâm.
  </p>

  @if(isset($websiteConfig))
    <ul class="list-unstyled">
      <li><i class="bi bi-geo-alt-fill me-2"></i> {{ $websiteConfig->address }}</li>
      <li><i class="bi bi-telephone-fill me-2"></i> {{ $websiteConfig->hotline }}</li>
      <li><i class="bi bi-envelope-fill me-2"></i> {{ $websiteConfig->email }}</li>
    </ul>
  @endif
</div>

      <!-- Liên kết nhanh -->
      <div class="col-md-3 mb-4">
        <h5 class="fw-bold">Liên kết nhanh</h5>
        <ul class="list-unstyled">
          <li><a href="#" class="text-dark text-decoration-none">Trang chủ</a></li>
          <li><a href="{{  route('user.products.list')}}" class="text-dark text-decoration-none">Cửa hàng</a></li>
          <!-- <li><a href="#" class="text-dark text-decoration-none">Bán chạy</a></li> -->
          <li><a href="#" class="text-dark text-decoration-none">Liên hệ</a></li>
        </ul>
      </div>

      <!-- Hỗ trợ khách hàng -->
      <div class="col-md-3 mb-4">
        <h5 class="fw-bold">Hỗ trợ</h5>
        <ul class="list-unstyled">
          <li><a href="#" class="text-dark text-decoration-none">Hướng dẫn mua hàng</a></li>
          <li><a href="#" class="text-dark text-decoration-none">Chính sách đổi trả</a></li>
          <li><a href="#" class="text-dark text-decoration-none">Chính sách bảo mật</a></li>
          <li><a href="#" class="text-dark text-decoration-none">FAQs</a></li>
        </ul>
      </div>

      <!-- Mạng xã hội -->
      <div class="col-md-3 mb-4">
        <h5 class="fw-bold">Kết nối với chúng tôi</h5>
        <a href="https://facebook.com" class="text-dark me-3"><i class="bi bi-facebook"></i> Facebook</a><br>
        <a href="https://twitter.com" class="text-dark me-3"><i class="bi bi-twitter"></i> Twitter</a><br>
        <a href="https://instagram.com" class="text-dark me-3"><i class="bi bi-instagram"></i> Instagram</a>
      </div>

    </div>

    <hr>

    <!-- <div class="d-flex justify-content-between small">
      <div>© {{ date('Y') }} <a href="#" class="text-dark fw-bold">MangaShop</a>. All rights reserved.</div>
      <div>
        <a href="https://github.com/" class="text-dark me-3">GitHub</a>
        <a href="https://getbootstrap.com/" class="text-dark">Bootstrap</a>
      </div>
    </div> -->

  </div>
</footer>
