<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('setting.app_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Pasar Murah" name="description" />
    <meta content="Muhamad Ahmadin" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ config('setting.logo_url') }}">

    <!-- App css -->
    <link href="{{ asset('admin') }}/css/bootstrap.min.css" rel="stylesheet" type="text/css"
        id="bootstrap-stylesheet" />
    <link href="{{ asset('admin') }}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin') }}/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

    <!-- Notification css (Toastr) -->
    <link href="{{ asset('admin') }}/libs/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

    @if (isset($datatable))
        <!-- Table datatable css -->
        <link href="{{ asset('admin') }}/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
            type="text/css" />

        <link href="{{ asset('admin') }}/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('admin') }}/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('admin') }}/libs/datatables/select.bootstrap4.min.css" rel="stylesheet"
            type="text/css" />
    @endif

    @stack('css')

    <style>
        .noti-scroll {
            height: 250px !important;
        }

        .amchart {
            width: 100%;
            height: 500px;
        }

    </style>

</head>

<body>
    @php
        // Notifikasi ada pesanan masuk-->
        $new_transactions = \App\Models\Transaction::with(['user'])
            ->orderBy('id', 'DESC')
            ->where('is_read', 0)
            ->get();

        // Notifikasi ada pembayaran berhasil dari notify-->
        $new_paid = \App\Models\Transaction::with(['user'])
            ->orderBy('id', 'DESC')
            ->where('notify_paid', 1)
            ->get();
    @endphp
    <!-- Begin page -->
    <div id="wrapper">
        <!-- Topbar Start -->
        <div class="navbar-custom">
            <ul class="list-unstyled topnav-menu float-right mb-0">

                <li class="dropdown notification-list dropdown d-none d-lg-inline-block ml-2">
                    <a class="nav-link dropdown-toggle mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ asset('') }}/img/flag/{{ Str::lower(Lang::locale()) == 'id' ? 'id.jpg' : 'en.jpg' }}" alt="lang-image" height="12">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <!-- item-->
                        <a href="{{ route('lang.change', Str::lower(Lang::locale()) == 'id' ? 'en' : 'id') }}" class="dropdown-item notify-item">
                            <img src="{{ asset('') }}/img/flag/{{ Str::lower(Lang::locale()) == 'id' ? 'en.jpg' : 'id.jpg' }}" alt="lang-image" class="mr-1" height="12"> <span
                                class="align-middle">{{ Str::lower(Lang::locale()) == 'id' ? 'English' : 'Indonesia' }}</span>
                        </a>
                    </div>
                </li>

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="mdi mdi-bell-outline noti-icon"></i>
                        @if ($new_transactions->count() > 0)
                            <span class="noti-icon-badge"></span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-lg">

                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="font-16 text-white m-0">
                                <span class="float-right">
                                    <a href="#" class="text-white">
                                        <small>Clear All</small>
                                    </a>
                                </span>Notification
                            </h5>
                        </div>

                        <div class="slimscroll noti-scroll">

                            @forelse ($new_transactions as $item)
                                <a href="{{ route('dashboard.transaction.show', $item->id) }}"
                                    class="dropdown-item notify-item">
                                    <div class="d-flex justify-content-between">
                                        <div class="kiri">
                                            <div class="notify-icon bg-info">
                                                <i class="mdi mdi-bell-outline"></i>
                                            </div>
                                            <p class="notify-details">Pesanan Baru
                                                <small class="text-muted">oleh: {{ $item->user->name ?? '-' }}</small>
                                            </p>
                                        </div>
                                        <div class="kanan">
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->locale('id_ID')->isoFormat('D MMM Y') }}</small>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <h2 class="text-center lead" style="margin-top: 25%;">Tidak ada notifikasi</h2>
                            @endforelse
                        </div>

                        <!-- All-->
                        <a href="{{ route('dashboard.histori_penjualan') }}"
                            class="dropdown-item text-primary notify-item notify-all">
                            View all
                            <i class="fi-arrow-right"></i>
                        </a>

                    </div>
                </li>

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="mdi mdi-email-outline noti-icon"></i>
                        @if ($new_paid->count() > 0)
                            <span class="noti-icon-badge"></span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-lg">

                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="font-16 text-white m-0">
                                <span class="float-right">
                                </span>Pembayaran
                            </h5>
                        </div>

                        <div class="slimscroll noti-scroll"
                            style="max-height: 300px !important;height:100% !important;">

                            <div class="inbox-widget" style="max-height: 300px !important; height:100% !important;">
                                @forelse ($new_paid as $item)
                                    <a
                                        href="{{ route('dashboard.transaction.show', $item->id) }}?notify_paid_seen=1">
                                        <div class="inbox-item">
                                            <div class="inbox-item-img">
                                                <div class="d-flex justify-content-between">
                                                    <div class="kiri d-flex justify-content-start">
                                                        <img src="{{ MyHelper::get_uploaded_file_url($item->user->avatar ?? '') }}"
                                                            class="rounded-circle mr-2" alt="Avatar" height="40"
                                                            width="40">
                                                        <p class="notify-details">{{ $item->user->name ?? '-' }} <br>
                                                            <small class="text-muted">melakukan pembayaran dengan
                                                                status: {!! $item->get_status_label() !!}</small> |
                                                            <small class="text-muted"
                                                                style="font-size: 10px !important;">{{ \Carbon\Carbon::parse($item->updated_at)->locale('id_ID')->isoFormat('D MMM Y') }}</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <h2 class="text-center lead" style="margin-top: 25%;">Tidak ada notifikasi</h2>
                                @endforelse
                            </div> <!-- end inbox-widget -->

                        </div>
                        <!-- All-->
                        <a href="javascript:void(0);" class="dropdown-item text-primary notify-item notify-all">
                            View all
                            <i class="fi-arrow-right"></i>
                        </a>

                    </div>
                </li>

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ MyHelper::get_uploaded_file_url(Auth::user()->avatar) }}" alt="user-image"
                            class="rounded-circle">
                        <span
                            class="d-none d-sm-inline-block ml-1 font-weight-medium">{{ Auth::user()->name }}</span>
                        <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow text-white m-0">Welcome !</h6>
                        </div>

                        <!-- item-->
                        <a href="{{ route('dashboard.master.user.profile') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-outline"></i>
                            <span>Profile</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item notify-item"><i
                                    class="mdi mdi-logout-varian"></i> Logout</button>
                        </form>

                    </div>
                </li>

            </ul>

            <!-- LOGO -->
            <div class="logo-box">
                <a href="{{ route('dashboard.index') }}" class="logo text-center logo-dark">
                    <span class="logo-lg">
                        <img src="{{ config('setting.logo_rect_url') }}" alt="" width="150" height="60">
                    </span>
                    <span class="logo-sm">
                        <!-- <span class="logo-lg-text-dark">U</span> -->
                        <img src="{{ config('setting.logo_url') }}" alt="" width="40">
                    </span>
                </a>

                <a href="{{ route('dashboard.index') }}" class="logo text-center logo-light">
                    <span class="logo-lg">
                        <img src="{{ config('setting.logo_rect_url') }}" alt="" height="22">
                    </span>
                    <span class="logo-sm">
                        <!-- <span class="logo-lg-text-dark">U</span> -->
                        <img src="{{ config('setting.logo_url') }}" alt="" height="24">
                    </span>
                </a>
            </div>

            <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                <li>
                    <button class="button-menu-mobile waves-effect waves-light">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </li>
            </ul>
        </div>
        <!-- end Topbar -->


        <!-- ========== Left Sidebar Start ========== -->
        @include('layouts.admin.sidebar')
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <div class="content">
                <!-- Start Content-->
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">

                                </div>
                                <h4 class="page-title">{{ $title ?? config('app.name') }}</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
