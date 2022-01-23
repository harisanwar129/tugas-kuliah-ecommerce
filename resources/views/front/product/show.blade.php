@extends('layouts.front')

@section('content')
    <div class="product-main-area mt-60 mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12 col-12 order-lg-1 order-1">
                    <!-- product-main-area-start -->
                    <div class="product-main-area">
                        <div class="row">
                            <div class="col-lg-5 col-md-6 col-12">
                                <div class="flexslider">
                                    <ul class="slides">
                                        <li data-thumb="{{ MyHelper::get_uploaded_file_url($product->thumbnail) }}">
                                            <img src="{{ MyHelper::get_uploaded_file_url($product->thumbnail) }}"
                                                alt="{{ $product->name }}" />
                                        </li>
                                        @foreach ($product->product_galleries as $item)
                                            <li data-thumb="{{ MyHelper::get_uploaded_file_url($item->thumbnail) }}">
                                                <img src="{{ MyHelper::get_uploaded_file_url($item->thumbnail) }}"
                                                    alt="{{ $product->name }}" />
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-6 col-12">
                                <div class="product-info-main">
                                    <div class="page-title">
                                        <h1>{{ $product->name }}</h1>
                                    </div>
                                    <div class="product-reviews-summary">
                                        <div class="reviews-actions">
                                            <a href="javascript:void(0)">{{ $product->views }}x {{ __('messages.seen') }}</a>
                                        </div>
                                    </div>
                                    <div class="product-info-price">
                                        <div class="price-final">
                                            @if ($product->is_discount)
                                            <span><span style="font-size: 12px;">Rp</span>
                                            {{ MyHelper::rupiah($product->price_striked ?? 0) }}</span>
                                            <span class="old-price"><span style="font-size: 12px;">Rp</span>
                                            {{ MyHelper::rupiah($product->price) }}</span>
                                            @else
                                            <span><span style="font-size: 12px;">Rp</span>
                                            {{ MyHelper::rupiah($product->price) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($product->stock > 0)
                                    <span id="stock_status"><i class="fa fa-check"></i> {{ __('messages.stock') }}: {{ $product->stock }}</span>
                                    @else
                                    <i class="fa fa-close" style="color:red"></i> {{ __('messages.stock') }}: 0
                                    @endif
                                    <p class="pt-2">Berat: {{ MyHelper::rupiah($product->weight) }} Gram</p>
                                    <div class="product-add-form">
                                        <form action="" id="product_form" method="POST" class="mt-2">
                                            @csrf
                                            <input type="hidden" name="formProductId" id="formProductId"
                                                value="{{ $product->id }}">
                                            <div class="quality-button">
                                                <input type="number" name="quantity" id="quantity" value="1" min="1"
                                                    max="99" />
                                            </div>
                                            <button id="add_to_cart" class="btn-hideung">{{ __('messages.add_to_cart') }}</button>
                                        </form>
                                    </div>
                                    <div class="product-social-links">
                                        <div class="product-addto-links">
                                            <form action="" id="product_wishlist_form" method="POST" class="mt-2">
                                                @csrf
                                                <input type="hidden" name="formWishlistProductId" id="formWishlistProductId"
                                                    value="{{ $product->id }}">
                                                <button id="add_to_wishlist" class="btn-sqr"><i class="fa fa-heart"></i></button>
                                            </form>
                                        </div>
                                        <div class="product-addto-links-text pt-4 text-center ">
                                            <h4 class="font-weight-bold">
                                                Deskripsi Produk
                                            </h4>
                                            </div>
                                            <br>
                                            <p>
                                                {{ $product->description }}
                                            </p>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- product-main-area-end -->
                    <!-- new-book-area-start -->

                    <!-- new-book-area-start -->
                </div>
                <div class="col-lg-3 col-md-12 col-12 order-lg-2 order-2">
                    <div class="shop-left">
                        <div class="left-title mb-20">
                            <h4>Produk Serupa</h4>
                        </div>
                        <div class="random-area mb-30">
                            <div class="product-active-2 owl-carousel">
                                @foreach ($related_products as $item)
                                    <div class="product-total-2">
                                        <div class="single-most-product bd mb-18">
                                            <div class="most-product-img">
                                                <a href="{{ route('product.show', $item->slug) }}" >
                                                    <img src="{{ MyHelper::get_uploaded_file_url($item->thumbnail) }}" alt="book"
                                                        class="primary" />
                                                </a>
                                            </div>
                                            <div class="most-product-content">

                                                <h4><a href="{{ route('product.show', $item->slug) }}">{{ Str::limit($item->name,15) }}</a></h4>
                                                <div class="product-price">
                                                    <ul>
                                                        @if ($item->is_discount)
                                                            <li>{{ MyHelper::rupiah($item->price_striked ?? 0) }}</li>
                                                            <li class="old-price">{{ MyHelper::rupiah($item->price) }}</li>
                                                        @else
                                                            <li>{{ MyHelper::rupiah($item->price) }}</li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="new-book-area mt-60">
                        <div class="section-title text-center mb-30">
                            <h3>Mungkin kamu tertarik</h3>
                        </div>
                        <div class="tab-active-2 owl-carousel">
                            <!-- single-product-start -->
                            @foreach ($popular_products as $item)
                            <!-- single-product-start -->
                            <div class="product-wrapper mx-2">
                                <div class="product-img" style="height: 200px;">
                                    <a href="{{ route('product.show', $item->slug) }}" >
                                        <img src="{{ MyHelper::get_uploaded_file_url($item->thumbnail) }}" alt="book"
                                            class="primary" />
                                    </a>
                                    <div class="quick-view">
                                        <a class="action-view" href="javascript:void(0)" id="{{ $item->id }}">
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
                                    <h4><a href="{{ route('product.show', $item->slug) }}">{{ $item->name }}</a></h4>
                                    <div class="product-price">
                                        <ul>
                                            @if ($item->is_discount)
                                                <li>{{ MyHelper::rupiah($item->price_striked ?? 0) }}</li>
                                                <li class="old-price">{{ MyHelper::rupiah($item->price) }}</li>
                                            @else
                                                <li>{{ MyHelper::rupiah($item->price) }}</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-link">
                                    <div class="add-to-link">
                                        <ul>
                                            <li><a href="{{ route('product.show', $item->slug) }}" title="Details"><i
                                                        class="fa fa-external-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                            <!-- single-product-end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.product_modal')
@endsection
