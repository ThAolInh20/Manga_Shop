<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    
    <!-- Logo -->
    <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center fw-bold me-4">
      <i class='bx bx-book-open text-primary me-1'></i> MangaShop
    </a>

    <!-- Search -->
    <form class="d-none d-md-flex ms-3" action="{{ route('user.products.list') }}" method="GET">
  <div class="input-group">
<input type="text" class="form-control" name="search" 
       value="{{ request('search') }}" 
       placeholder="Tìm sách, tác giả..." />    <button class="btn btn-outline-primary" type="submit">
      <i class='bx bx-search'></i>
    </button>
  </div>
</form>

    <!-- Right -->
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <li class="nav-item me-2"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
      <li class="nav-item me-2"><a class="nav-link" href="{{ route('user.products.list') }}">Danh sách sản phẩm</a></li>
      <li class="nav-item me-2"><a class="nav-link" href="#">Liên hệ</a></li>
       <li class="nav-item">
         <a class="nav-link p-0" href="#">
          <i class="bx bx-cart fs-4"></i>
        
        </a>
      </li>
        <li class="nav-item dropdown me-md-3">
          <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center" href="#" data-bs-toggle="dropdown">
            <i class="bx bx-user fs-4"></i>
          </a>
          
          <ul class="dropdown-menu dropdown-menu-end">
            @auth
             <li>
                <h6 class="dropdown-header">
                    👋 Xin chào, <strong>{{ Auth::user()->name }}</strong>
                </h6>
            </li>
            <li><a class="dropdown-item" href="#"><i class="bx bx-user me-2"></i> Hồ sơ cá nhân</a></li>
            <li><a class="dropdown-item" href="#"><i class="bx bx-shopping-bag me-2"></i> Đơn hàng của tôi</a></li>
            @endauth
            <li><a class="dropdown-item" href="#"><i class="bx bx-heart me-2"></i> Danh sách yêu thích</a></li>
            <li><hr class="dropdown-divider"></li>
            @auth
            <li><a class="dropdown-item" href="{{ route('password.change') }}"><i class="bx bx-lock me-2"></i> Đổi mật khẩu</a></li>
            <li>

              <form method="POST" action="{{ route('user.logout') }}">
                @csrf
                <button type="submit" class="dropdown-item"><i class="bx bx-log-out me-2"></i> Đăng xuất</button>
              </form>
            </li>
            @endauth
            @guest
             <li><a class="dropdown-item" href="{{ route('login') }}"><i class="bx bx-user me-2"></i> Đăng nhập</a></li>
             
            <li><a class="dropdown-item" href="{{ route('register') }}"><i class="bx bx-user-plus me-2"></i> Đăng ký</a></li>
            @endguest
          </ul>
        </li>
    

      
    </ul>
  </div>
</nav>
