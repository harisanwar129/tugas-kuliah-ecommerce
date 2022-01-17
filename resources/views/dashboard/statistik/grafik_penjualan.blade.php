@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card-box">
                <h4 class="header-title mb-3">Statistik Penjualan</h4>

                {{-- Statistik Bar Harian --}}
                @include('dashboard.statistik.components.bar_penjualan')

            </div>
        </div><!-- end col-->

        <div class="col-lg-12 col-xl-12">
            <div class="card-box">
                <h4 class="header-title mb-3">Produk Terlaris</h4>
                {{-- Statistik Bar Harian --}}
                @include('dashboard.statistik.components.pie_top_sales_product', ['jumlah_produk' => 10])
            </div>
        </div><!-- end col-->
    </div>
    <!-- end row -->


@endsection

@push('js')
    <!-- AMChart -->
    <script src="{{ asset('admin/js/amchart/core.js') }}"></script>
    <script src="{{ asset('admin/js/amchart/charts.js') }}"></script>
    <script src="{{ asset('admin/js/amchart/themes/animated.js') }}"></script>

    <script>
        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end
    </script>
@endpush
