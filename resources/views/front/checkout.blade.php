@extends('layouts.front')

@section('content')
    <div class="entry-header-area mt-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="entry-header-title">
                        <h2>Checkout</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- entry-header-area-end -->

    <!-- checkout-area-start -->
    <div class="checkout-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('checkout.store') }}" method="POST" class="myForm">
                        @csrf
                        <input type="hidden" name="city_from" id="city_from" value="{{ config('setting.city_id') }}">
                        <input type="hidden" name="shipping_service_name" id="shipping_service_name" value="">
                        <input type="hidden" name="shipping_service_code" id="shipping_service_code" value="">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-12">
                                <div class="checkbox-form">
                                    <h3>Informasi Nota</h3>

                                    <div class="row">
                                        @if ($errors->any())
                                            <div class="col-12 mb-2">
                                                <ul>
                                                    @foreach ($errors->all() as $item)
                                                        <li class="text-danger">
                                                            {{ $item }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <div class="col-lg-6 col-md-6 col-12 ">
                                            <div class="checkout-form-list">
                                                <label>Nama Lengkap <span class="required">*</span></label>
                                                <input type="text" placeholder="" name="customer_name"
                                                    value="{{ $user->name }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-12">
                                            <div class="checkout-form-list">
                                                <label>Email <span class="required">*</span></label>
                                                <input type="text" name="email" placeholder="" value="{{ $user->email }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-12">
                                            <div class="checkout-form-list">
                                                <label>Alamat <span class="required">*</span></label>
                                                <input type="text" placeholder="Alamat" name="address"
                                                    value="{{ $user->address }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="checkout-form-list">
                                                <label>Kurir <span class="required">*</span></label>
                                                <select name="courier" id="courier" class="form-control">
                                                    <option value="">{{ __('messages.choose') }}</option>
                                                    @foreach ($couriers as $item)
                                                        <option value="{{ $item->code }}">{{ $item->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="checkout-form-list">
                                                <label>Phone <span class="required">*</span></label>
                                                <input type="text" name="phone" value="{{ $user->phone }}"
                                                    placeholder="Nomor HP Aktif">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="checkout-form-list">
                                                <label>Provinsi Tujuan <span class="required">*</span></label>
                                                <select name="province_to" id="province_to" class="form-control">
                                                    <option value="">Pilih</option>
                                                    @foreach ($provinces as $item)
                                                        <option value="{{ $item->province_id }}">{{ $item->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="checkout-form-list">
                                                <label>Kota Tujuan <span class="required">*</span></label>
                                                <select name="city_to" id="city_to" class="form-control">
                                                    <option value="">Pilih Provinsi Dulu</option>
                                                </select>
                                            </div>
                                        </div>
                                        @if ($setting->city != null)
                                            <div class="col-md-12 text-center">
                                                <p>Pesanan akan dikirim dari {{ $setting->city->title }}, Provinsi
                                                    {{ $setting->city->province->title }}</p>
                                            </div>
                                        @endif

                                    </div>
                                    <div class="different-address">
                                        <div class="order-notes">
                                            <div class="checkout-form-list">
                                                <label>Catatan</label>
                                                <textarea placeholder="Catatan order" rows="10" cols="30" id="checkout-mess"
                                                    name="notes"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-12">
                                <div class="your-order">
                                    <h3>Pesanan Anda</h3>

                                    <div class="your-order-table table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th class="product-name">Produk</th>
                                                    <th class="product-total">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $total = 0;
                                                    $total_weight = 0;
                                                @endphp
                                                @foreach ($items as $item)
                                                    <tr class="cart_item">
                                                        <td class="product-name">
                                                            {{ $item->product->name }} <strong class="product-quantity">
                                                                ×
                                                                {{ $item->quantity }}</strong>
                                                        </td>
                                                        <td class="product-total">
                                                            <span class="amount">Rp
                                                                {{ MyHelper::rupiah($item->subtotal) }}</span>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $total += $item->subtotal;
                                                        $total_weight += $item->product->weight * $item->quantity;
                                                    @endphp
                                                @endforeach
                                                <tr class="cart_item">
                                                    <td class="product-name">
                                                        Total Berat</strong>
                                                    </td>
                                                    <td class="product-total">
                                                        <span class="amount">
                                                            {{ MyHelper::rupiah($total_weight) }} <b>Gram</b></span>
                                                    </td>
                                                </tr>
                                                <tr class="shipping">
                                                    <th class="product-name">
                                                        ONGKOS KIRIM
                                                        <input type="hidden" name="shipping_service" id="shipping_service">
                                                    </th>
                                                    <td id="ongkirOptions">
                                                        Pilih Kurir & Kota Tujuan
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr class="order-total">
                                                    <th>Order Total</th>
                                                    <td>
                                                        <input type="hidden" name="order_total" id="order_total_original"
                                                            value="{{ $total }}">
                                                        <input type="hidden" name="weight_total" id="weight_total"
                                                            value="{{ $total_weight }}">
                                                        <strong>
                                                            <span class="amount">
                                                                Rp
                                                                <span
                                                                    id="order_total">{{ MyHelper::rupiah($total) }}</span>
                                                            </span>
                                                        </strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="payment-method">
                                        <div class="order-button-payment">
                                            <input type="submit" class="formSubmitter disabled onloading" value="Buat Pesanan">
                                            @if (config('setting.city_id') == null)
                                                <div class="alert alert-danger mt-2">Tidak bisa melakukan transaksi karena
                                                    aplikasi belum siap digunakan (Kota Pengiriman belum diset)</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        var servicesCode = [];
        var servicesName = [];
        function disableCheckoutForm(status = true) {
            if (status == true) {
                $(".formSubmitter").attr('disabled', true).addClass('onloading');
                $(".myForm").attr('onsubmit', 'return false');
            } else {
                $(".formSubmitter").attr('disabled', false).removeClass('onloading');
                $(".myForm").attr('onsubmit', 'return true');
            }
        }

        function cekOngkir(city_from, city_to, courier, weight = 1000) {
            revertOrderTotal('Tunggu sebentar...');
            $('#ongkirOptions').html(`<img class="" src="{{ asset('img/loading.gif') }}" width="20" alt="">`);
            disableCheckoutForm(true)
            $.ajax({
                url: "{{ route('rajaongkir.cek_ongkir') }}",
                method: "POST",
                type: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    city_from: city_from,
                    city_to: city_to,
                    courierCode: courier,
                    weight: weight
                },
                success: function(response) {
                    disableCheckoutForm(false);
                    $('#ongkirOptions').html(``);
                    var services = `<ul>`
                    servicesCode = [];
                    servicesName = [];

                    if (response.data.warning == true) {
                        swal("Peringatan!", response.meta.message, {
                            icon: "warning",
                            buttons: {
                                confirm: {
                                    className: 'btn btn-danger'
                                }
                            },
                        });
                        disableCheckoutForm(true);
                        revertOrderTotal(response.meta.message);
                    } else {
                        if (response.data[0].costs.length > 0) {
                            for (i = 0; i < response.data[0].costs.length; i++) {
                                // auto select first service of each service
                                var serviceCost = response.data[0].costs[i].cost[0].value;
                                var isCheck = '';
                                if (i == 0) {
                                    var isCheck = 'checked';
                                    var order_total_original_with_shipping = parseInt($(
                                            '#order_total_original')
                                        .val()) + parseInt(serviceCost);
                                    $('#order_total').text(formatRupiah(order_total_original_with_shipping
                                        .toString()))
                                    $('#shipping_service_code').val(response.data[0].costs[i].service);
                                    $('#shipping_service_name').val(response.data[0].costs[i].description);
                                }
                                var serviceCostFormatted = formatRupiah(serviceCost.toString());
                                servicesCode.push(response.data[0].costs[i].service);
                                servicesName.push(response.data[0].costs[i].description);
                                services +=
                                    `
                                    <li>
                                        <input type="radio" class="shipping_service_prices" name="shipping_service_price" id="${i}" value="${response.data[0].costs[i].cost[0].value}" ${isCheck}>
                                        <label for="${i}">
                                            ${response.data[0].costs[i].service}: <span class="amount">Rp ${serviceCostFormatted}</span>
                                        </label>
                                    </li>
                                    `
                            }
                            services += `</ul>`;
                            $('#ongkirOptions').html(services);
                        } else {

                            disableCheckoutForm(true);
                            revertOrderTotal('Jasa Kurir Tidak Ada');
                        }
                    }
                },
                error: function(err) {
                    swal("Error!", "Telah terjadi kesalahan pada server", {
                        icon: "error",
                        buttons: {
                            confirm: {
                                className: 'btn btn-danger'
                            }
                        },
                    });
                },
                timeout: function() {
                    swal("Error!", "Timeout!", {
                        icon: "error",
                        buttons: {
                            confirm: {
                                className: 'btn btn-danger'
                            }
                        },
                    });
                }
            })
        }

        function revertOrderTotal(message = 'Pilih Kurir & Kota Tujuan') {
            $('#order_total').text(formatRupiah($('#order_total_original').val().toString()))
            $('#ongkirOptions').text(message);
        }

        function checkElement() {
            var courierCode = $('#courier').val();
            var cityFrom = $('#city_from').val();
            var cityTo = $('#city_to').val();
            var weightTotal = $('#weight_total').val();
            if (courierCode != "" && cityFrom != "" && cityTo != "") {
                cekOngkir(cityFrom, cityTo, courierCode, weightTotal);
            } else {
                disableCheckoutForm(true);
                revertOrderTotal();
            }
        }

        $(document).ready(function() {
            disableCheckoutForm(true);

            $('#province_to').on('change', function() {
                let province_to = $(this).val();
                if (province_to != "") {
                    $.ajax({
                        url: `{{ url('city/fetch') }}/${province_to}`,
                        success: function(response) {
                            let initialContent =
                                `<option selected value="">-Silahkan Pilih-</option>`
                            let newOptions = ``

                            response.forEach(city => {
                                newOptions +=
                                    `<option value="${city.city_id}">${city.title}</option>`
                            })

                            $("#city_to").html(initialContent + newOptions).val("")
                            checkElement()
                        },
                        error: function() {
                            swal("Error!", "Telah terjadi kesalahan pada server!", {
                                icon: "error",
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-danger'
                                    }
                                },
                            });
                        },
                        timeout: function() {
                            swal("Error!", "Timeout!", {
                                icon: "error",
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-danger'
                                    }
                                },
                            });
                        }
                    })
                } else {
                    $("#city_to").html("")
                }
            })

            $('#courier, #city_from, #city_to').on('change', function() {
                checkElement();
            })

            $(document).on('change', '.shipping_service_prices', function() {
                var selectedPrice = $(this).val();
                var serviceId = $(this).attr('id');
                var order_total_original_with_shipping = parseInt($('#order_total_original')
                    .val()) + parseInt(selectedPrice);
                $('#order_total').text(formatRupiah(order_total_original_with_shipping.toString()))
                $('#shipping_service_code').val(servicesCode[serviceId]);
                $('#shipping_service_name').val(servicesName[serviceId]);
            })
        })
    </script>
@endpush
