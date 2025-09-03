<!DOCTYPE html>
<html lang="vi" class="light-style layout-navbar-fixed" dir="ltr" data-theme="theme-default">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'BookNest')</title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

  <!-- Core CSS (Sneat) -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <!-- Helpers -->
  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
</head>
<body>

  <!-- Navbar -->
  @include('user.partials.navbar')

  <!-- Content -->
  <div class="content-wrapper pt-4">
    <div class="container-xxl flex-grow-1 container-p-y">
      @yield('content')
      <div id="vue-suggest-books"></div>
    </div>
  </div>

  <!-- Footer -->
  @include('user.partials.footer')

  <!-- Core JS -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

  <!-- Main JS -->
  <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
