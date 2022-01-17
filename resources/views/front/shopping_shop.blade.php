@extends('layouts.front')

@section('content')
    <!-- shop-main-area-start -->
    <div class="shop-main-area mt-70 mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-12 col-12 order-lg-1 order-2 mt-sm-50 mt-xs-40">
                    <div class="shop-left">
                        @include('layouts.filter_options')
                    </div>
                </div>
                <div class="col-lg-9 col-md-12 col-12 order-lg-2 order-1">

                    <div class="text-center">
                        @if ($shop->shop_logo != null || $shop->shop_logo != '')
                            <div class="category-image mb-30 ">
                                <a href="javascript:void(0)"><img width="100"
                                        src="{{ MyHelper::get_uploaded_file_url($shop->shop_logo) }}" alt="banner" /></a>
                            </div>
                        @endif
                        <h4><b>{{ $shop->shop_name }}</b></h4>
                        <p class="lead" style="font-size: 16px;">{{ $shop->shop_desc }}</p>
                        <hr>
                    </div>
                    <div class="section-title-5 mb-30 mt-30">
                        <h2>{{ __('messages.label_product') }}</h2>
                    </div>
                    <hr>
                    <!-- tab-area-start -->
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="th">
                            <div class="row">
                                @foreach ($products as $item)
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
                                                            <li><span class="sale">{{ __('messages.label_new') }}</span></li>
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

                                            <<div class="product-details text-center">
                                                <h4><a
                                                        href="{{ route('product.show', $item->slug) }}">{{ Str::limit($item->name, 30) }}</a>
                                                </h4>
                                                <div class="product-price">
                                                    <ul>
                                                        @if ($item->is_discount)
                                                            <li>{{ MyHelper::rupiah($item->price_striked ?? 0) }}</li>
                                                            <li class="old-price">
                                                                {{ MyHelper::rupiah($item->price) }}</li>
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
                    <!-- pagination-area-start -->
                    {{ $products->links() }}
                    <!-- pagination-area-end -->
                </div>
            </div>
        </div>
    </div>
    <!-- shop-main-area-end -->

    @include('layouts.product_modal')
@endsection
