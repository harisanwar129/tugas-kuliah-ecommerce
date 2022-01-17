@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card-box tilebox-one">
                <i class="icon-layers float-right m-0 h2 text-muted"></i>
                <h6 class="text-muted text-uppercase mt-0">{{ __('messages.label_orders') }}</h6>
                <h3 class="my-3" data-plugin="">{{ ($transactions->count() - $transactions->where('status', 'batal')->count()) }} </h3>
                <span class="text-muted">{{ $transactions->where('status', 'batal')->count() }} {{ __('messages.label_canceled') }}</span>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card-box tilebox-one">
                <i class="icon-paypal float-right m-0 h2 text-muted"></i>
                <h6 class="text-muted text-uppercase mt-0">{{ __('messages.label_revenue') }}</h6>
                <h3 class="my-3">Rp <span data-plugin="">{{ MyHelper::rupiah($revenue) }}</span></h3>

            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card-box tilebox-one">
                <i class="icon-chart float-right m-0 h2 text-muted"></i>
                <h6 class="text-muted text-uppercase mt-0">{{ __('messages.label_pending_revenue') }}</h6>
                <h3 class="my-3">Rp<span data-plugin="">{{ MyHelper::rupiah($pending_revenue) }}</span>
                </h3>

            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card-box tilebox-one">
                <i class="icon-rocket float-right m-0 h2 text-muted"></i>
                <h6 class="text-muted text-uppercase mt-0">{{ __('messages.label_product_sold') }}</h6>
                <h3 class="my-3" data-plugin="">{{ MyHelper::rupiah($product_sold_count) }}</h3>
            </div>
        </div>
    </div>
    <!-- end row -->


    <div class="row">
        <div class="col-lg-6 col-xl-8">
            <div class="card-box">
                <h4 class="header-title mb-3">{{ __('messages.label_sale_statistic') }}</h4>

                {{-- Statistik Bar Harian --}}
                @include('dashboard.statistik.components.bar_penjualan')

            </div>
        </div><!-- end col-->

        <div class="col-lg-6 col-xl-4">
            <div class="card-box">
                <h4 class="header-title mb-3">{{ __('messages.label_top_product') }}</h4>
                {{-- Statistik Bar Harian --}}
                @include('dashboard.statistik.components.pie_top_sales_product')
            </div>
        </div><!-- end col-->
    </div>
    <!-- end row -->


    <div class="row">
        <div class="col-xl-12">
            <div class="card-box">

                <h4 class="header-title mb-3">5 TOP {{ __('messages.label_orders') }}</h4>

                <div class="table-responsive">
                    <table class="table table-bordered table-nowrap mb-0">
                        <thead>
                            <th width="10">#</th>
                            <th width="120">{{ __('messages.label_date') }}</th>
                            <th>Customer</th>
                            <th width="100">Status</th>
                            <th>Total</th>
                            <th width="100">{{ __('messages.label_action') }}</th>
                        </thead>
                        <tbody>
                            @foreach ($top_transaction as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @php
                                            $is_new = '';
                                            if ($item->is_read == 0) {
                                                $is_new = '<br><span class="badge badge-pill badge-danger">Baru</span>';
                                            }
                                            echo \Carbon\Carbon::parse($item->created_at)
                                                ->locale(Lang::locale())
                                                ->isoFormat('D MMM Y | H:s A') . $is_new;
                                        @endphp
                                    </td>
                                    <td>{{ $item->customer_name }}</td>
                                    <td>{!! MyHelper::get_status_label($item->status) !!}</td>
                                    <td>Rp {{ MyHelper::rupiah($item->subtotal) }}</td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a href="{{ route('dashboard.transaction.show', $item->id) }}" class="btn btn-sm waves-effect waves-light btn-info mr-1"><i class="mdi mdi-information"></i> Detail</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
