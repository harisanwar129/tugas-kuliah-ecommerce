@extends('layouts.front')

@section('content')
    <!-- entry-header-area-start -->
    <div class="entry-header-area mt-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="entry-header-title">
                        <h2>Cart</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- entry-header-area-end -->
    <!-- cart-main-area-start -->
    <div class="cart-main-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <form action="#">
                        <div class="table-content table-responsive mb-15 border-1">
                            <table>
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th class="product-thumbnail">{{ __('messages.label_photo') }}</th>
                                        <th class="product-name">{{ __('messages.label_product') }}</th>
                                        <th class="product-price">{{ __('messages.label_price') }}</th>
                                        <th class="product-quantity">{{ __('messages.label_qty') }}</th>
                                        <th class="product-subtotal">Total</th>
                                        <th class="product-remove">{{ __('messages.label_action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total = 0;
                                    @endphp
                                    @forelse ($items as $key=> $item)
                                        @php
                                            $price = $item->product->is_discount == 1 ? $item->product->price_striked : $item->product->price;
                                            $subtotal = $item->quantity * $price;
                                            $total += $subtotal;
                                        @endphp
                                        <tr id="{{ $item->id }}">
                                            <th>{{ $items->firstItem()+$key }}</th>
                                            <td class="product-thumbnail"><a href="#"><img
                                                        src="{{ MyHelper::get_uploaded_file_url($item->product->thumbnail) }}"
                                                        alt="foto produk" /></a>
                                            </td>
                                            <td class="product-name">
                                                <a href="{{ route('product.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                            </td>
                                            <td class="product-price"><span class="amount">
                                                    @if ($item->product->is_discount)
                                                        <span class="old-price"
                                                            style="font-size: 14px !important;"><span
                                                                style="font-size: 14px !important;">Rp</span>
                                                            {{ MyHelper::rupiah($item->product->price) }}</span> <br>
                                                        <span style="font-size:18px !important;"><span
                                                                style="font-size: 16px !important;">Rp</span>
                                                            {{ MyHelper::rupiah($item->product->price_striked ?? 0) }}</span>
                                                    @else
                                                        <span style="font-size:18px !important;"><span
                                                                style="font-size: 16px !important;">Rp</span>
                                                            {{ MyHelper::rupiah($item->product->price) }}</span>
                                                    @endif
                                                </span></td>
                                            <td class="product-quantity"><input class="quantities" type="number"
                                                    id="{{ $item->id }}" value="{{ $item->quantity }}">
                                                <button type="button" class="btn-icon btn-update-qty"
                                                    id="{{ $item->id }}">
                                                    <i class="fa fa-floppy-o"></i>
                                                </button>
                                            </td>
                                            <td class="product-subtotal">Rp
                                                <span
                                                    id="subtotal_{{ $item->id }}">{{ MyHelper::rupiah($subtotal) }}</span>
                                            </td>
                                            <td class="product-remove"><button class="delete_cart" type="button"
                                                    id="{{ $item->id }}"><i class="fa fa-times"></i></button></td>
                                        </tr>
                               @empty
                                                                <tr>
                                                                    <td colspan="7">Tidak Ada Produk</td>
                                                                </tr>
                                   
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="pull-right">
                            {{ $items->links() }}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-6 col-12">
                    <div class="buttons-cart mb-30">
                        <ul>
                            <li><a href="{{ route('welcome') }}">{{ __('messages.continue_shopping') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="cart_totals">
                        <h2>Total</h2> <br>
                        <table>
                            <tbody>
                                <tr class="order-total">
                                    <th>Total</th>
                                    <td>
                                        <strong>
                                            <span class="amount" id="total_amount">Rp
                                                {{ MyHelper::rupiah($total) }}</span>
                                        </strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br><br>
                        <div class="wc-proceed-to-checkout text-right">
                            <a href="{{ route('checkout') }}">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- cart-main-area-end -->
@endsection

@push('js')
    <script>


        $(document).ready(function() {
            var user_id = '{{ auth()->user()->id }}';
            var the_total = 0;

            $(document).on('click', '.delete_cart', function(e) {
                var cart_id = $(this).attr('id');
                var the_data = {
                    cart_id: cart_id,
                    user_id: user_id
                };
                e.preventDefault();

                swal({
                    title: 'Hapus dari keranjang?',
                    text: "Apakah anda yakin menghapus barang ini dari keranjang anda?",
                    icon: 'warning',
                    buttons: {
                        confirm: {
                            text: 'Ya!',
                            className: 'btn btn-success'
                        },
                        cancel: {
                            visible: true,
                            className: 'btn btn-danger'
                        }
                    }
                }).then((Delete) => {
                    if (Delete) {
                        // do ajax delete
                        $.ajax({
                            type: "POST",
                            url: "{{ route('v1.delete_cart_item') }}",
                            data: the_data,
                            dataType: "json",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                if (res.data.is_success == true) {
                                    var sessionMessage = res.meta.message;
                                    toastr.options = {
                                        "closeButton": true,
                                        "debug": false,
                                        "newestOnTop": false,
                                        "progressBar": true,
                                        "positionClass": "toast-bottom-right",
                                        "preventDuplicates": false,
                                        "onclick": null,
                                        "showDuration": "300",
                                        "hideDuration": "1000",
                                        "timeOut": "5000",
                                        "extendedTimeOut": "1000",
                                        "showEasing": "swing",
                                        "hideEasing": "linear",
                                        "showMethod": "fadeIn",
                                        "hideMethod": "fadeOut"
                                    }

                                    $(document).ready(function onDocumentReady() {
                                        toastr.success(sessionMessage);
                                    });
                                    $('tr#' + cart_id).fadeOut();
                                    reCalculateTotal(user_id)
                                    reloadCartItems(user_id);
                                } else {
                                    const el = document.createElement('div')
                                    el.innerHTML =
                                        "Silahkan <a href='{{ route('register') }}'>Registrasi</a> dahulu untuk melanjutkan"
                                    swal({
                                        title: 'Registrasi Dahulu',
                                        content: el,
                                        icon: 'warning',
                                        buttons: {
                                            confirm: {
                                                className: 'btn btn-warning'
                                            }
                                        }
                                    });
                                }
                            },
                            error: function(res) {
                                console.log(res);
                                swal({
                                    title: 'Gagal',
                                    text: res.metamessage,
                                    icon: 'error',
                                    buttons: {
                                        confirm: {
                                            className: 'btn btn-warning'
                                        }
                                    }
                                });
                            }
                        })


                    } else {
                        swal.close();
                    }
                });
            })

            $(document).on('click', '.btn-update-qty', function(e) {
                var cart_id = $(this).attr('id');
                var new_qty = $(this).siblings().val();
                e.preventDefault();
                var the_data = {
                    cart_id: cart_id,
                    new_qty: new_qty,
                    user_id: user_id
                };
                var thisButton = $(this);
                thisButton.addClass('onloading');
                $.ajax({
                    type: "POST",
                    url: "{{ route('v1.update_cart_qty') }}",
                    data: the_data,
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        // console.log(res);
                        setTimeout(function() {
                            thisButton.removeClass('onloading');
                        }, 1000)
                        if (res.data.is_success == true) {
                            var sessionMessage = res.meta.message;
                            toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": true,
                                "positionClass": "toast-bottom-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }

                            $(document).ready(function onDocumentReady() {
                                toastr.success(sessionMessage);
                            });

                            $('#subtotal_' + cart_id).text(res.data.new_subtotal)
                            reCalculateTotal(user_id);
                            reloadCartItems(user_id);
                        } else {
                            const el = document.createElement('div')
                            el.innerHTML =
                                "Silahkan <a href='{{ route('register') }}'>Registrasi</a> dahulu untuk melanjutkan"
                            swal({
                                title: 'Registrasi Dahulu',
                                content: el,
                                icon: 'warning',
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-warning'
                                    }
                                }
                            });
                        }
                    },
                    error: function(res) {
                        console.log(res);
                        swal({
                            title: 'Gagal',
                            text: res.meta.message,
                            icon: 'error',
                            buttons: {
                                confirm: {
                                    className: 'btn btn-warning'
                                }
                            }
                        });
                    }
                })
            })
        })
    </script>
@endpush
