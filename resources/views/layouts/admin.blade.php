<!DOCTYPE html>
<html lang="vi" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>@yield('title', 'Admin Dashboard')</title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  <!-- Fonts & Icons -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

  @include('admin.website_custom.custom_theme')

  <!-- Helpers -->
  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

  <!-- Config (phải load sau helpers.js) -->
  <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
  <!-- Layout wrapper -->
  <div  class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

      <!-- Sidebar -->
      @include('partials.sidebar')

      <!-- Layout container -->
      <div class="layout-page">

        <!-- Navbar -->
        

        <!-- Content wrapper -->
        <div class="content-wrapper">

          <!-- Content -->
          <div  class="container-xxl flex-grow-1 container-p-y">
            @yield('content')
          </div>
          <!-- / Content -->

          <!-- Footer -->
          @include('partials.footer')

        </div>
        <!-- / Content wrapper -->

      </div>
      <!-- / Layout page -->
    </div>
    <!-- / Layout container -->
  </div>
  <!-- / Layout wrapper -->

  <!-- Core JS -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

  <!-- Menu JS (bắt buộc cho sidebar & dropdown) -->
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

  <!-- Main JS -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

  @stack('scripts')
</body>
</html>

