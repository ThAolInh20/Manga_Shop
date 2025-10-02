<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    
    <!-- Logo -->
    <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center fw-bold me-4" title="Trang chủ MangaShop">
      <img class="logo" src="{{ asset('storage/logo/logo.png') }}" alt="MangaShop" style="height: 60px; object-fit: contain; margin-right: 10px;">
    </a>

    <!-- Search -->
    <form class="d-none d-md-flex ms-3" action="{{ route('user.products.list') }}" method="GET" title="Tìm kiếm sách">
      <div class="input-group">
        <input type="text" class="form-control" name="search" 
              value="{{ request('search') }}" 
              placeholder="Tìm sách, tác giả..." />
        <button class="btn btn-outline-primary" type="submit" title="Tìm kiếm">
          <i class='bx bx-search'></i>
        </button>
      </div>
    </form>

    <!-- Right -->
    <ul class="navbar-nav flex-row align-items-center ms-auto">

      {{-- Home / Product List --}}
      <li class="nav-item me-2">
        <a class="nav-link" href="{{ route('home') }}" title="Trang chủ">Trang chủ</a>
      </li>
      <li class="nav-item me-2">
        <a class="nav-link" href="{{ route('user.products.list') }}" title="Xem cửa hàng">Cửa hàng</a>
      </li>

      {{-- Giỏ hàng --}}
      <li class="nav-item me-3 position-relative">
        <a class="nav-link p-0" href="{{ route('user.cart.list') }}" title="Giỏ hàng">
          <i class="bx bx-cart fs-4"></i>
          @if($cartCount>0)
          <span class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $cartCount }}
          </span>
          @endif
        </a>
      </li>

      {{-- Notification --}}
      <li class="nav-item dropdown me-3" id="notification-app" title="Thông báo">
        <notification-dropdown></notification-dropdown>
        @if($notificationCount>0)
        <span class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          {{ $notificationCount }}
        </span>
        @endif
      </li>

      {{-- User --}}
      <li class="nav-item dropdown me-3">
        <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center" href="#" data-bs-toggle="dropdown" title="Tài khoản của bạn">
          <i class="bx bx-user fs-4"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          @auth
            <li>
              <h6 class="dropdown-header">
                👋 Xin chào, <strong>{{ Auth::user()->name }}</strong>
              </h6>
            </li>
            <li><a class="dropdown-item" href="{{ route('user.profi') }}" title="Thông tin cá nhân"><i class="bx bx-user me-2"></i> Thông tin cá nhân</a></li>
            <li><a class="dropdown-item" href="{{ route('user.order.list') }}" title="Danh sách đơn hàng"><i class="bx bx-shopping-bag me-2"></i> Đơn hàng của tôi</a></li>
          @endauth
          <li><a class="dropdown-item" href="{{ route('user.wishlist.list') }}" title="Sách yêu thích"><i class="bx bx-heart me-2"></i> Danh sách yêu thích</a></li>
          <li><hr class="dropdown-divider"></li>
          @auth
            <li><a class="dropdown-item" href="{{ route('password.change') }}" title="Đổi mật khẩu"><i class="bx bx-lock me-2"></i> Đổi mật khẩu</a></li>
            <li>
              <form method="POST" action="{{ route('user.logout') }}">
                @csrf
                <button type="submit" class="dropdown-item" title="Đăng xuất"><i class="bx bx-log-out me-2"></i> Đăng xuất</button>
              </form>
            </li>
          @endauth
          @guest
            <li><a class="dropdown-item" href="{{ route('login') }}" title="Đăng nhập"><i class="bx bx-user me-2"></i> Đăng nhập</a></li>
            <li><a class="dropdown-item" href="{{ route('register') }}" title="Tạo tài khoản mới"><i class="bx bx-user-plus me-2"></i> Đăng ký</a></li>
          @endguest
        </ul>
      </li>
    </ul>
  </div>
</nav>
