<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    
    <!-- Logo -->
    <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center fw-bold me-4">
      <i class='bx bx-book-open text-primary me-1'></i> MangaShop
    </a>

    <!-- Search -->
    <form class="d-none d-md-flex ms-3">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Tìm sách, tác giả..." />
        <button class="btn btn-outline-primary" type="submit"><i class='bx bx-search'></i></button>
      </div>
    </form>

    <!-- Right -->
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <li class="nav-item me-2"><a class="nav-link" href="#">Home</a></li>
      <li class="nav-item me-2"><a class="nav-link" href="#">Manga</a></li>
      <li class="nav-item me-2"><a class="nav-link" href="#">Ấn phẩm Manga</a></li>
    <li class="nav-item">
        <a class="btn btn-sm btn-primary position-relative" href="#">
          <i class='bx bx-cart'></i>
          <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">2</span>
        </a>
      </li>
      {{-- Nếu chưa login thì hiện nút Đăng nhập --}}
      @guest
        <li class="nav-item me-md-3">
          <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">
            <i class='bx bx-user'></i> Đăng nhập
          </a>
        </li>
        
      @endguest

      {{-- Nếu đã login thì hiện avatar + menu --}}
      @auth
        <li class="nav-item dropdown me-md-3">
          <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center" href="#" data-bs-toggle="dropdown">
            <div class="avatar avatar-online">
              <img src="{{ Auth::user()->image ?? asset('assets/img/avatars/1.png') }}" 
                   alt="{{ Auth::user()->name }}" 
                   class="w-px-40 h-auto rounded-circle" />
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#"><i class="bx bx-user me-2"></i> Hồ sơ</a></li>
            <li><a class="dropdown-item" href="#"><i class="bx bx-cog me-2"></i> Cài đặt</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item"><i class="bx bx-log-out me-2"></i> Đăng xuất</button>
              </form>
            </li>
          </ul>
        </li>
      @endauth

      
    </ul>
  </div>
</nav>
