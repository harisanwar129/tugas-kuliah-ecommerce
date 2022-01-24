<!doctype html>
<html class="no-js" lang="en">


<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ config('setting.app_name') }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/fontawesome.min.css" integrity="sha384-jLKHWM3JRmfMU0A5x5AkjWkw/EYfGUAGagvnfryNV3F9VqM98XiIH7VBGVoxVSc7" crossorigin="anonymous">

<!-- PWA  -->
<meta name="theme-color" content="#6777ef"/>
<link rel="apple-touch-icon" href="{{ asset('gemilang.jpg') }}">
<link rel="manifest" href="{{ asset('/manifest.json') }}">

    @include('layouts.header_css')
    @stack('css')

</head>

<body>
<script src="{{ asset('/sw.js') }}"></script>
<script>
    if (!navigator.serviceWorker.controller) {
        navigator.serviceWorker.register("/sw.js").then(function (reg) {
            console.log("Service worker has been registered for scope: " + reg.scope);
        });
    }
</script>
    <!-- header-area-start -->
    <header>
        <!-- header-top-area-start -->
        {{-- <div class="header-top-area">
            <div class="container">
                <div class="row">
                    {{-- <div class="col-lg-6 col-md-6 col-12">
                        <div class="language-area">
                            <ul>
								<li><img src="{{ asset('') }}/img/flag/{{ Str::lower(Lang::locale()) == 'id' ? 'id.jpg' : 'en.jpg' }}" alt="flag" width="18" /><a href="#">{{ MyHelper::get_lang(Lang::locale()) }}<i class="fa fa-angle-down"></i></a>
									<div class="header-sub">
										<ul>
											<li><a href="{{ route('lang.change', Str::lower(Lang::locale()) == 'id' ? 'en' : 'id') }}"><img src="{{ asset('') }}/img/flag/{{ Str::lower(Lang::locale()) == 'id' ? 'en.jpg' : 'id.jpg' }}" alt="flag" width="25" />{{ Str::lower(Lang::locale()) == 'id' ? 'English' : 'Indonesia' }}</a></li>
										</ul>
									</div>
								</li>
							</ul>
                        </div>
                    </div> --}}
                    {{-- <div class="col-lg-6 col-md-6 col-12">
                        <div class="account-area text-right">
                            <ul>
                                @if (Auth::check())
                                    <li><a class="font-weight-bold" href="{{ route('register') }}">{{ __('messages.my_account') }}
                                            <b>({{ Auth::user()->name }})</b></a></li>
                                @else
                                    <li><a class="font-weight-bold" href="{{ route('login') }}">Login</a></li>
                                    <li><a class="font-weight-bold" href="{{ route('register') }}">Register</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- header-top-area-end -->
        <!-- header-mid-area-start -->
        <div class="header-mid-area ptb-40">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-12">
                        <div class="header-search d-lg-none d-block">
                            <form action="{{ route('front.search') }}" method="POST" class="d-flex justify-content-between">
                                @csrf
                                <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="{{ __('messages.find_something') }}..." />
                                <button type="submit" class="btn btn-sqr"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-12">
                        <div class="logo-area text-center logo-xs-mrg">
                            <a href="{{ route('welcome') }}"><img src="{{ config('setting.logo_rect_url') == '' ? asset('img/yourlogo.png') : config('setting.logo_rect_url') }}" alt="logo"
                                    class="img-responsive" style="width:100%;height:50%" /></a>
                        </div>
                    </div>
                    

                    @if (Auth::check() && Auth::user()->role == 'MEMBER')
                        <div class="col-lg-4 col-md-12 col-12 " style="text:center">
                            <div class="my-cart">
                                <ul class="d-flex justify-content-between">
                                    @php
                                        $my_cart_item = App\Models\TransactionItem::with(['product'])->where('user_id', Auth::user()->id)->where('transaction_id', null)->get();
                                        $my_wishlist_item = App\Wishlist::with(['product'])->where('user_id', Auth::user()->id)->get();
                                    @endphp
                                    {{-- <li>
                                        <a href="{{ route('my_wishlist') }}"><i class="fa fa-heart"></i></a>
                                        <span
                                            id="cart_items_count">{{ $my_wishlist_item->count() }}</span>
                                        <div class="mini-cart-sub">
                                            <div class="cart-product" id="cart-product-content">
                                                @foreach ($my_wishlist_item as $wishlist_item)
                                                    <div class="single-cart floating-cart-item" id="{{ $wishlist_item->id }}">
                                                        <div class="cart-img">
                                                            <a href="{{ route('product.show', $wishlist_item->product->slug) }}"><img src="{{ MyHelper::get_uploaded_file_url($wishlist_item->product->thumbnail) }}" alt="book" /></a>
                                                        </div>
                                                        <div class="cart-info">
                                                            <h5><a href="{{ route('product.show', $wishlist_item->product->slug) }}">{{ Str::limit($wishlist_item->product->name, 16) }}</a></h5>
                                                        </div>

                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="cart-bottom">
                                                <a class="view-cart" href="{{ route('my_wishlist') }}">Lihat Favorite</a>
                                            </div>
                                        </div></li> --}}
                                    <li><a href="{{ route('my_cart') }}"><i class="fa fa-shopping-cart"></i> </a>
                                        <span
                                            id="cart_items_count">{{ App\Models\TransactionItem::where('user_id', Auth::user()->id)->where('transaction_id', null)->count() }}</span>
                                        <div class="mini-cart-sub">
                                            <div class="cart-product" id="cart-product-content">
                                                @foreach ($my_cart_item as $cart_item)
                                                    <div class="single-cart floating-cart-item" id="{{ $cart_item->id }}">
                                                        <div class="cart-img">
                                                            <a href="{{ route('product.show', $cart_item->product->slug) }}"><img src="{{ MyHelper::get_uploaded_file_url($cart_item->product->thumbnail) }}" alt="book" /></a>
                                                        </div>
                                                        <div class="cart-info">
                                                            <h5><a href="{{ route('product.show', $cart_item->product->slug) }}">{{ Str::limit($cart_item->product->name, 16) }}</a></h5>
                                                            <p>{{ $cart_item->quantity }} x Rp {{ MyHelper::rupiah($cart_item->price) }}</p>
                                                        </div>
                                                        <div class="cart-icon">
                                                            <a href="javascript:void(0)" class="floating_delete_cart" id="{{ $cart_item->id }}"><i class="fa fa-remove"></i></a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="cart-totals">
                                                <h5>Total <span
                                                        id="cart_items_total">{{ MyHelper::rupiah(Auth::user()->get_cart_total_price()) }}</span>
                                                </h5>
                                            </div>
                                            <div class="cart-bottom">
                                                <a class="view-cart" href="{{ route('my_cart') }}">Lihat Keranjang</a>
                                            </div>
                                             <div class="cart-bottom">
                                                <a class="view-cart" href="{{ route('my_wishlist') }}">Lihat Wishlist</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif

               

                  
                </div>

               <div class="row pb-35">
                    <div class="col-lg-12 col-md-3 col-12 " >
                        <div class="account-area text-right">
                            <ul>
                                @if (Auth::check())
                                    <li><a class="font-weight-bold" href="{{ route('register') }}">{{ __('messages.my_account') }}
                                            <b>({{ Auth::user()->name }})</b></a></li>
                                @else
                                    <li><a class="font-weight-bold" href="{{ route('login') }}">Login</a></li>
                                    <li><a class="font-weight-bold" href="{{ route('register') }}">Register</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
               
               </div>

            </div>
        </div>
        <!-- header-mid-area-end -->
        <!-- main-menu-area-start -->
        <div class="main-menu-area d-md-none d-none d-lg-block sticky-header-1" id="header-sticky">
            <div class="container">
                <div class="row pb-1">
                    <div class="col-lg-8">
                        <div class="menu-area">
                            <nav>
                                <ul>
                                    @php
                                        $menu = \App\Menu::where('visible', 1)
                                            ->whereIn('position', ['header', 'header_footer'])
                                            ->orderBy('urutan', 'ASC')
                                            ->get();
                                    @endphp
                                    @foreach ($menu as $item)
                                        @if ($item->sub_menus->count() > 0)
                                            <li><a href="#">{{ $item->nama }}<i class="fa fa-angle-down"></i></a>
                                                <div class="sub-menu sub-menu-2">
                                                    <ul>
                                                        @foreach ($item->sub_menus as $sub)
                                                            <li><a
                                                                    href="{{ $sub->get_link() }}">{{ $sub->nama }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </li>
                                        @else
                                            <li><a href="{{ $item->link }}">{{ $item->nama }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-lg-4 ">
                        <div class="header-search">
                            <form action="{{ route('front.search') }}" method="POST" class="d-flex justify-content-between">
                                @csrf
                                <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="{{ __('messages.find_something') }}..." />
                                <button type="submit" class="btn btn-sqr"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- main-menu-area-end -->
        <!-- mobile-menu-area-start -->
        <div class="mobile-menu-area d-lg-none d-block fix">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mobile-menu">
                            <nav id="mobile-menu-active">
                                <ul id="nav">
                                    @foreach ($menu as $item)
                                        @if ($item->sub_menus->count() > 0)
                                            <li>
                                                <a href="javascript:void(0)">{{ $item->nama }}</a>
                                                <ul>
                                                    @foreach ($item->sub_menus as $sub)
                                                        <li>
                                                            <a href="{{ $sub->get_link() }}">{{ $sub->nama }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @else
                                            <li><a href="{{ $item->link }}">{{ $item->nama }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- mobile-menu-area-end -->
    </header>
    <!-- header-area-end -->

