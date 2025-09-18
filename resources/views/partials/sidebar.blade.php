<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
      <span class="app-brand-text demo menu-text fw-bold">Admin</span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    <!-- Dashboard -->
    <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <a href="{{ route('admin.dashboard') }}" class="menu-link">
        <i class="menu-icon bx bx-home-circle"></i>
        <div>Dashboard</div>
      </a>
    </li>

    <!-- Quản lý đơn hàng -->
    <li class="menu-item {{ request()->routeIs('orders.index') ? 'active' : '' }}">
      <a href="{{ route('orders.index') }}" class="menu-link">
        <i class="menu-icon bx bx-cart"></i>
        <div>Quản lý đơn hàng</div>
      </a>
    </li>

    <!-- Quản lý voucher -->
    <li class="menu-item {{ request()->routeIs('vouchers.index') ? 'active' : '' }}">
      <a href="{{ route('vouchers.index') }}" class="menu-link">
        <i class="menu-icon bx bx-purchase-tag-alt"></i>
        <div>Quản lý voucher</div>
      </a>
    </li>

    <!-- Quản lý sản phẩm -->
    <li class="menu-item {{ request()->routeIs('products.*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon bx bx-package"></i>
        <div>Quản lý sản phẩm</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('products.index') ? 'active' : '' }}">
          <a href="{{ route('products.index') }}" class="menu-link">
            <i class="bx bx-list-ul"></i>
            <div>Danh sách sản phẩm</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('products.create') ? 'active' : '' }}">
          <a href="{{ route('products.create') }}" class="menu-link">
            <i class="bx bx-plus-circle"></i>
            <div>Thêm sản phẩm</div>
          </a>
        </li>
      </ul>
    </li>

    @if(Auth::user()->role == 0)
      <!-- Quản lý danh mục -->
      <li class="menu-item {{ request()->routeIs('categories.index') ? 'active' : '' }}">
        <a href="{{ route('categories.index') }}" class="menu-link">
          <i class="menu-icon bx bx-category"></i>
          <div>Danh sách danh mục</div>
        </a>
      </li>

      <!-- Quản lý nhà cung cấp -->
      <li class="menu-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
        <a href="{{ route('suppliers.index') }}" class="menu-link">
          <i class="menu-icon bx bx-store"></i>
          <div>Quản lý nhà cung cấp</div>
        </a>
      </li>

      <!-- Quản lý tài khoản -->
      <li class="menu-item {{ request()->routeIs('accounts.*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon bx bx-user"></i>
          <div>Quản lý tài khoản</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item {{ request()->routeIs('accounts.index') ? 'active' : '' }}">
            <a href="{{ route('accounts.index') }}" class="menu-link">
              <i class="bx bx-list-ul"></i>
              <div>Danh sách tài khoản</div>
            </a>
          </li>
          <li class="menu-item {{ request()->routeIs('accounts.create') ? 'active' : '' }}">
            <a href="{{ route('accounts.create') }}" class="menu-link">
              <i class="bx bx-plus-circle"></i>
              <div>Thêm tài khoản</div>
            </a>
          </li>
        </ul>
      </li>

      <!-- Cấu hình website -->
      <li class="menu-item {{ request()->routeIs('website_custom.edit') ? 'active' : '' }}">
        <a href="{{ route('website_custom.edit') }}" class="menu-link">
          <i class="menu-icon bx bx-cog"></i>
          <div>Cấu hình website</div>
        </a>
      </li>
    @endif

    <!-- Đăng xuất -->
    <li class="menu-item">
      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="menu-link btn btn-link text-start w-100">
          <i class="bx bx-power-off me-2"></i>
          <span>Đăng xuất</span>
        </button>
      </form>
    </li>

  </ul>
</aside>
<!-- / Menu -->
