<!DOCTYPE html>
<html lang="en">

<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover, shrink-to-fit=no">
    <meta name="description" content="{{ config('setting.app_name') }}">
    <meta name="developer" content="Muhamad Ahmadin">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#100DD1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Title-->
    <title>{{ config('setting.app_name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    <!-- Favicon-->
    <link rel="icon" href="{{ asset('') }}/mobile/img/icons/icon-72x72.png">
    <!-- Apple Touch Icon-->
    <link rel="apple-touch-icon" href="{{ asset('') }}/mobile/img/icons/icon-96x96.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('') }}/mobile/img/icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('') }}/mobile/img/icons/icon-167x167.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('') }}/mobile/img/icons/icon-180x180.png">
    <!-- CSS Libraries-->
    <link rel="stylesheet" href="{{ asset('') }}/mobile/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('') }}/mobile/css/animate.css">
    <link rel="stylesheet" href="{{ asset('') }}/mobile/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('') }}/mobile/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('') }}/mobile/css/lineicons.min.css">
    <link rel="stylesheet" href="{{ asset('') }}/mobile/css/magnific-popup.css">
    <!-- Stylesheet-->
    <link rel="stylesheet" href="{{ asset('') }}/mobile/style.css">
    <!-- Web App Manifest-->
    <link rel="manifest" href="{{ asset('') }}/mobile/manifest.json">
  </head>
  <body>
    <!-- Preloader-->
    <div class="preloader" id="preloader">
      <div class="spinner-grow text-secondary" role="status">
        <div class="sr-only">Loading...</div>
      </div>
    </div>
    <!-- Header Area-->
    <div class="header-area" id="headerArea">
      <div class="container h-100 d-flex align-items-center justify-content-between">
        <!-- Logo Wrapper-->
        <div class="logo-wrapper"><a href="{{ asset('') }}/mobile/home.html"><img src="{{ asset('') }}/mobile/img/core-img/logo-small.png" alt=""></a></div>
        <!-- Search Form-->
        <div class="top-search-form">
          <form action="#" method="">
            <input class="form-control" type="search" placeholder="Enter your keyword">
            <button type="submit"><i class="fa fa-search"></i></button>
          </form>
        </div>
        <!-- Navbar Toggler-->
        <div class="suha-navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#suhaOffcanvas" aria-controls="suhaOffcanvas"><span></span><span></span><span></span></div>
      </div>
    </div>
    <div class="offcanvas offcanvas-start suha-offcanvas-wrap" tabindex="-1" id="suhaOffcanvas" aria-labelledby="suhaOffcanvasLabel">
      <!-- Close button-->
      <button class="btn-close btn-close-white text-reset" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      <!-- Offcanvas body-->
      <div class="offcanvas-body">
        <!-- Sidenav Profile-->
        <div class="sidenav-profile">
          <div class="user-profile"><img src="{{ asset('') }}/mobile/img/bg-img/9.jpg" alt=""></div>
          <div class="user-info">
            <h6 class="user-name mb-1">Suha Sarah</h6>
            <p class="available-balance">Total balance $<span class="counter">583.67</span></p>
          </div>
        </div>
        <!-- Sidenav Nav-->
        <ul class="sidenav-nav ps-0">
          <li><a href="{{ asset('') }}/mobile/profile.html"><i class="lni lni-user"></i>My Profile</a></li>
          <li><a href="{{ asset('') }}/mobile/notifications.html"><i class="lni lni-alarm lni-tada-effect"></i>Notifications<span class="ms-3 badge badge-warning">3</span></a></li>
          <li class="suha-dropdown-menu"><a href="{{ asset('') }}/mobile/#"><i class="lni lni-cart"></i>Shop Pages</a>
            <ul>
              <li><a href="{{ asset('') }}/mobile/shop-grid.html">- Shop Grid</a></li>
              <li><a href="{{ asset('') }}/mobile/shop-list.html">- Shop List</a></li>
              <li><a href="{{ asset('') }}/mobile/single-product.html">- Product Details</a></li>
              <li><a href="{{ asset('') }}/mobile/featured-products.html">- Featured Products</a></li>
              <li><a href="{{ asset('') }}/mobile/flash-sale.html">- Flash Sale</a></li>
            </ul>
          </li>
          <li><a href="{{ asset('') }}/mobile/pages.html"><i class="lni lni-empty-file"></i>All Pages</a></li>
          <li class="suha-dropdown-menu"><a href="{{ asset('') }}/mobile/wishlist-grid.html"><i class="lni lni-heart"></i>My Wishlist</a>
            <ul>
              <li><a href="{{ asset('') }}/mobile/wishlist-grid.html">- Wishlist Grid</a></li>
              <li><a href="{{ asset('') }}/mobile/wishlist-list.html">- Wishlist List</a></li>
            </ul>
          </li>
          <li><a href="{{ asset('') }}/mobile/settings.html"><i class="lni lni-cog"></i>Settings</a></li>
          <li><a href="{{ asset('') }}/mobile/intro.html"><i class="lni lni-power-switch"></i>Sign Out</a></li>
        </ul>
      </div>
    </div>
