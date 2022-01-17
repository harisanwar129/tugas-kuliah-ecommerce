@extends('layouts.front')

@section('content')
    <!-- banner-area-start -->
    <div class="banner-area banner-res-large pt-30 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="single-banner mb-30">
                        <div class="banner-img">
                            <a href="#"><img src="img/banner/1.png" alt="banner" /></a>
                        </div>
                        <div class="banner-text">
                            <h4>{{ __('messages.free_shipping') }}</h4>
                            <p>{{ __('messages.free_shipping_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="single-banner mb-30">
                        <div class="banner-img">
                            <a href="#"><img src="img/banner/2.png" alt="banner" /></a>
                        </div>
                        <div class="banner-text">
                            <h4>{{ __('messages.easy_and_simple') }}</h4>
                            <p>{{ __('messages.easy_and_simple_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="single-banner mb-30">
                        <div class="banner-img">
                            <a href="#"><img src="img/banner/3.png" alt="banner" /></a>
                        </div>
                        <div class="banner-text">
                            <h4>{{ __('messages.forever_access') }}</h4>
                            <p>{{ __('messages.forever_access_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="single-banner mb-30">
                        <div class="banner-img">
                            <a href="#"><img src="img/banner/4.png" alt="banner" /></a>
                        </div>
                        <div class="banner-text">
                            <h4>{{ __('messages.help_support') }}</h4>
                            <p>{{ __('messages.help_support_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner-area-end -->
    <!-- slider-area-start -->
    @include('components.slider')
    <!-- slider-area-end -->

    <!-- banner-static-area-start -->
    <h2 style="text-align:center">KATEGORI</h2>
    <div class="banner-static-area bg mt-5">
        <div class="container">
            <div class="row tab-active-2 owl-carousel">
                @foreach ($categories as $item)
                    <div class="mr-2">
                        <div class="banner-shadow-hover xs-mb" style="height: 200px;">
                            <a href="{{ route('front.shopping.shopping_category', $item->slug) }}" style="height: 100%;">
                                <img src="{{ MyHelper::get_uploaded_file_url($item->thumbnail) }}" alt="banner"
                                    style="border: 1px solid gray;border-radius:4px; height: 100%; object-fit:cover;width:100%;" />
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- banner-static-area-end -->

        <!-- product-area-start -->
        <div class="product-area pt-95 xs-mb">
            <div class="container">



                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title text-center mb-50">
                            <h2>{{ __('messages.label_top_interesting') }}</h2>
                            <p>{{ __('messages.label_top_interesting_desc') }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <!-- tab-menu-start -->
                        <div class="tab-menu mb-40 text-center">
                            <ul class="nav justify-content-center">
                                <li><a class="active" href="#newProducts"
                                        data-toggle="tab">{{ __('messages.label_new_products') }} </a></li>
                                <li><a href="#discountProducts"
                                        data-toggle="tab">{{ __('messages.label_discount_products') }}</a></li>
                            </ul>
                        </div>
                        <!-- tab-menu-end -->
                    </div>
                </div>
                <!-- tab-area-start -->
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="newProducts">
                        <div class="row">
                            @foreach ($new_products as $item)
                                <!-- single-product-start -->
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 mb-4">
                                    <div class="product-wrapper">
                                        <div class="product-img" style="height: 200px;">
                                            <a href="{{ route('product.show', $item->slug) }}">
                                                <img src="{{ MyHelper::get_uploaded_file_url($item->thumbnail) }}"
                                                    alt="book" class="primary" />
                                            </a>
                                            <div class="quick-view">
                                                <a class="action-view" href="javascript:void(0)"
                                                    id="{{ $item->id }}">
                                                    <i class="fa fa-search-plus"></i>
                                                </a>
                                            </div>
                                            <div class="product-flag">
                                                <ul>
                                                    @if (strtotime($item->created_at) > strtotime('-7 day'))
                                                        <li><span
                                                                class="sale">{{ __('messages.label_new') }}</span>
                                                        </li>
                                                    @endif
                                                    @if ($item->is_discount)
                                                        @php
                                                            $percentDisc = (($item->price - $item->price_striked) * 100) / $item->price;
                                                        @endphp
                                                        <li><span
                                                                class="discount-percentage">-{{ number_format($percentDisc, 0, ',', '.') }}%</span>
                                                        </li>

                                                    @endif
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="product-details text-center">
                                            <h4><a
                                                    href="{{ route('product.show', $item->slug) }}">{{ $item->name }}</a>
                                            </h4>
                                            <div class="product-price">
                                                <ul>
                                                    @if ($item->is_discount)
                                                        <li>{{ MyHelper::rupiah($item->price_striked ?? 0) }}</li>
                                                        <li class="old-price">{{ MyHelper::rupiah($item->price) }}
                                                        </li>
                                                    @else
                                                        <li>{{ MyHelper::rupiah($item->price) }}</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-link">
                                            <div class="add-to-link">
                                                <ul>
                                                    <li><a href="{{ route('product.show', $item->slug) }}"
                                                            title="Details"><i class="fa fa-external-link"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade" id="discountProducts">
                        <div class="row">
                            @foreach ($discount_products as $item)
                                <!-- single-product-start -->
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 mb-4">
                                    <div class="product-wrapper">
                                        <div class="product-img" style="height: 200px;">
                                            <a href="{{ route('product.show', $item->slug) }}">
                                                <img src="{{ MyHelper::get_uploaded_file_url($item->thumbnail) }}"
                                                    alt="book" class="primary" />
                                            </a>
                                            <div class="quick-view">
                                                <a class="action-view" href="javascript:void(0)"
                                                    id="{{ $item->id }}">
                                                    <i class="fa fa-search-plus"></i>
                                                </a>
                                            </div>
                                            <div class="product-flag">
                                                <ul>
                                                    @if (strtotime($item->created_at) > strtotime('-7 day'))
                                                        <li><span class="sale">new</span></li>
                                                    @endif
                                                    @if ($item->is_discount)
                                                        @php
                                                            $percentDisc = (($item->price - $item->price_striked) * 100) / $item->price;
                                                        @endphp
                                                        <li><span
                                                                class="discount-percentage">-{{ number_format($percentDisc, 0, ',', '.') }}%</span>
                                                        </li>

                                                    @endif
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="product-details text-center">
                                            <h4><a
                                                    href="{{ route('product.show', $item->slug) }}">{{ $item->name }}</a>
                                            </h4>
                                            <div class="product-price">
                                                <ul>
                                                    @if ($item->is_discount)
                                                        <li>{{ MyHelper::rupiah($item->price_striked ?? 0) }}</li>
                                                        <li class="old-price">{{ MyHelper::rupiah($item->price) }}
                                                        </li>
                                                    @else
                                                        <li>{{ MyHelper::rupiah($item->price) }}</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-link">
                                            <div class="add-to-link">
                                                <ul>
                                                    <li><a href="{{ route('product.show', $item->slug) }}"
                                                            title="Details"><i class="fa fa-external-link"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- tab-area-end -->
            </div>
        </div>
        <!-- product-area-end -->

        @include('layouts.product_modal')
    @endsection
