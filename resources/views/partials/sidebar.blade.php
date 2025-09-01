<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" width="30">
      </span>
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
    <li class="menu-item {{ request()->routeIs('orders.*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon bx bx-cart"></i>
        <div>Quản lý đơn hàng</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('orders.index') ? 'active' : '' }}">
          <a href="{{ route('orders.index') }}" class="menu-link">
            <i class="bx bx-list-ul"></i>
            <div>Thống kê doanh thu</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('orders.index') ? 'active' : '' }}">
          <a href="{{ route('orders.index') }}" class="menu-link">
            <i class="bx bx-list-ul"></i>
            <div>Danh sách đơn hàng</div>
          </a>
        </li>

        
      </ul>
    </li>
    <!-- Quản lý Voucher -->
<li class="menu-item {{ request()->routeIs('vouchers.*') ? 'active open' : '' }}">
  <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon bx bx-purchase-tag-alt"></i>
    <div>Quản lý Voucher</div>
  </a>
  <ul class="menu-sub">
    <li class="menu-item {{ request()->routeIs('vouchers.index') ? 'active' : '' }}">
      <a href="{{ route('vouchers.index') }}" class="menu-link">
        <i class="bx bx-list-ul"></i>
        <div>Danh sách voucher</div>
      </a>
    </li>
    
  </ul>
</li>

    <!-- Quản lý danh mục -->
    <li class="menu-item {{ request()->routeIs('categories.*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon bx bx-category"></i>
        <div>Quản lý danh mục</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('categories.index') ? 'active' : '' }}">
          <a href="{{ route('categories.index') }}" class="menu-link">
            <i class="bx bx-list-ul"></i>
            <div>Danh sách danh mục</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('categories.create') ? 'active' : '' }}">
          <a href="{{ route('categories.create') }}" class="menu-link">
            <i class="bx bx-plus-circle"></i>
            <div>Thêm danh mục</div>
          </a>
        </li>
      </ul>
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

    <!-- Quản lý nhà cung cấp -->
    <li class="menu-item {{ request()->routeIs('suppliers.*') || request()->routeIs('product_suppliers.*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon bx bx-store"></i>
        <div>Quản lý nhà cung cấp</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
          <a href="{{ route('suppliers.index') }}" class="menu-link">
            <i class="bx bx-list-ul"></i>
            <div>Danh sách nhà cung cấp</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('product_suppliers.index') ? 'active' : '' }}">
          <a href="{{ route('product_suppliers.index') }}" class="menu-link">
            <i class="bx bx-cube-alt"></i>
            <div>Sản phẩm của nhà cung cấp</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Tài khoản -->
    <li class="menu-item {{ request()->routeIs('accounts.*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon bx bx-user"></i>
        <div>Quản lý tài khoản</div>
      </a>
       <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
          <a href="{{ route('accounts.index') }}" class="menu-link">
            <i class="bx bx-list-ul"></i>
            <div>Danh sách tài khoản</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('product_suppliers.index') ? 'active' : '' }}">
          <a href="{{ route('accounts.create') }}" class="menu-link">
            <i class="bx bx-cube-alt"></i>
            <div>Thêm tài khoản admin hoặc staff</div>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</aside>
<!-- / Menu -->
