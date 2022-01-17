@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <!-- <div class="panel-heading">
                        <h4>Invoice</h4>
                    </div> -->
                <div class="panel-body">
                    <div class="clearfix">
                        <div class="float-sm-left">
                            <img src="{{ config('setting.logo_rect_url') }}" alt="Logo Aplikasi" width="200">
                            {{-- <h4 class="text-uppercase mt-0">{{ config('app.name') }}</h4> --}}
                        </div>
                        <div class="float-sm-right mt-4 mt-sm-0">
                            <h5>Invoice # {{ $transaction->trx_id ?? '' }} <br>
                                <small>{{ $transaction->sid }}</small>
                                <br>
                            </h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="float-sm-left mt-4">
                                <address>
                                    <strong>{{ $transaction->user->name }}.</strong><br>
                                    {{ $transaction->user->address }}<br>
                                    {{ $transaction->user->email }}<br>
                                    <abbr title="Nomor Telepon">Telp:</abbr> {{ $transaction->user->phone }}
                                </address>
                            </div>
                            <div class="mt-4 text-sm-right">
                                <p><strong>Tanggal Pesan: </strong>
                                    {{ \Carbon\Carbon::parse($transaction->created_at)->locale('id_ID')->isoFormat('dddd, D MMMM Y') }}
                                </p>
                                <p><strong>Jam: </strong>
                                    {{ \Carbon\Carbon::parse($transaction->created_at)->locale('id_ID')->isoFormat('H:ss a') }}
                                </p>
                                <p><strong>Status Pesanan: </strong> {!! $transaction->get_status_label() !!}</p>
                                <p><strong>Trx ID: </strong> #{{ $transaction->trx_id }}</p>
                                <p><strong>Pengiriman: </strong> Dari {{ $setting->city->title . ' Ke ' . $transaction->city->title }}</p>
                            </div>
                        </div><!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-wrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Produk</th>
                                            <th>Deskripsi</th>
                                            <th>Kuantitas</th>
                                            <th>Harga Satuan</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $grand_total = 0;
                                            $total_weight = 0;
                                        @endphp
                                        @foreach ($transaction->transaction_items as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ route('product.show', $item->product->slug) }}">
                                                        {{ Str::limit($item->product->name, 20) }}
                                                    </a>
                                                </td>
                                                <td>{{ Str::limit($item->product->description, 40) }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>Rp {{ MyHelper::rupiah($item->price) }}</td>
                                                <td>Rp {{ MyHelper::rupiah($item->subtotal) }}</td>
                                            </tr>
                                            @php
                                                $grand_total += $item->subtotal;
                                                $total_weight += $item->product->weight * $item->quantity;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-10">
                            <div class="text-right mt-4">
                                <p><b>Berat Produk Total:</b> {{ MyHelper::rupiah($total_weight) }} Gram</p>
                                <p><b>Subtotal:</b> Rp {{ MyHelper::rupiah($grand_total) }}</p>
                                @if ($transaction->shipping_courier_code != NULL)
                                <p><b>Ongkir ({{ Str::upper($transaction->shipping_courier_code) . ' | ' . $transaction->shipping_service_name }}):</b> Rp {{ MyHelper::rupiah($transaction->shipping_service_price) }}</p>
                                @else
                                <p><b>Ongkir: 0</b></p>
                                @endif
                                <h3>Rp {{ MyHelper::rupiah($grand_total + $transaction->shipping_service_price) }}</h3>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-print-none">
                        @if ($transaction->status == 'berhasil' && $transaction->state == NULL)
                            <form action="{{ route('dashboard.transaction.kirim_pesanan', $transaction->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger waves-effect waves-light buttonKirimPesanan">Kirimkan Pesanan <i class="fa fa-bus"></i></button>
                            </form>
                        @endif
                        <h6>Status Pengiriman: {!! $transaction->get_status_pengiriman() !!}</h6>
                        <div class="float-right">
                            <button class="btn btn-info waves-effect waves-light btn-check-transaction"
                                id="{{ $transaction->id }}"><i class="fas fa-undo"></i> Cek Status Transaksi</button>
                            <a href="javascript:window.print()" class="btn btn-dark waves-effect waves-light"><i
                                    class="fa fa-print"></i> Cetak</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('js')
    <script>
        function checkTransactionStatus(transaction_id) {
            console.log(transaction_id);
            $.ajax({
                url: "{{ route('v1.ipaymu.check_transaction') }}",
                method: "POST",
                type: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    transaction_id: transaction_id
                },
                success: function(res) {
                    console.log(transaction_id);
                    console.log(res)
                    if (res.data != null) {
                        if (res.data.Data) {
                            // sudah ada trx id, tinggal cek respon dari api
                            if (res.data.Data.Status == 0) {
                                swal({
                                    title: 'Pending',
                                    text: 'Status pembayaran pending',
                                    icon: 'info',
                                    buttons: {
                                        confirm: {
                                            className: 'btn btn-info'
                                        }
                                    },
                                });
                            } else if (res.data.Data.Status == 1) {
                                swal({
                                    title: 'Berhasil',
                                    text: 'Status pembayaran berhasil',
                                    icon: 'success',
                                    buttons: {
                                        confirm: {
                                            className: 'btn btn-success'
                                        }
                                    },
                                });
                            } else if (res.data.Data.Status == 2) {
                                swal({
                                    title: 'Batal',
                                    text: 'Status pembayaran dibatalkan',
                                    icon: 'danger',
                                    buttons: {
                                        confirm: {
                                            className: 'btn btn-danger'
                                        }
                                    },
                                });
                            } else {
                                swal({
                                    title: res.data.Data.statusDesc,
                                    text: 'Status pembayaran ' + res.data.Data.statusDesc,
                                    icon: 'info',
                                    buttons: {
                                        confirm: {
                                            className: 'btn btn-info'
                                        }
                                    },
                                });
                            }
                        } else {
                            // belum ada trx id, artinya belum melakukan pembayaran sama sekali, belum ter generate trx_id nya
                            if (res.data.status == 'PENDING') {
                                swal({
                                    title: 'Pending',
                                    text: 'Status pembayaran pending',
                                    icon: 'info',
                                    buttons: {
                                        confirm: {
                                            className: 'btn btn-info'
                                        }
                                    },
                                });
                            } else if (res.data.status == 'gagal') {
                                swal({
                                    title: 'Gagal',
                                    text: 'Status pembayaran gagal',
                                    icon: 'warning',
                                    buttons: {
                                        confirm: {
                                            className: 'btn btn-danger'
                                        }
                                    },
                                });
                            } else if (res.data.status == 'berhasil') {
                                swal({
                                    title: 'Berhasil',
                                    text: 'Status pembayaran berhasil',
                                    icon: 'success',
                                    buttons: {
                                        confirm: {
                                            className: 'btn btn-success'
                                        }
                                    },
                                });
                            } else {
                                swal({
                                    title: res.data.status,
                                    text: 'Status pembayaran ' + res.data.status,
                                    icon: 'info',
                                    buttons: {
                                        confirm: {
                                            className: 'btn btn-info'
                                        }
                                    },
                                });
                            }
                        }

                    } else {
                        swal({
                            title: 'Informasi Status',
                            text: 'Status pembayaran: ' + res.data.Data.Status,
                            icon: 'info',
                            buttons: {
                                confirm: {
                                    className: 'btn btn-info'
                                }
                            },
                        });
                    }
                },
                error: function(res) {
                    console.log('error');
                    swal({
                        title: 'Gagal',
                        text: 'Trx ID tidak ditemukan, pastikan sistem berjalan dengan keadaan online',
                        icon: 'warning',
                        buttons: {
                            confirm: {
                                className: 'btn btn-danger'
                            }
                        },
                    });
                }
            });
        }
        $(document).ready(function() {
            $('.btn-check-transaction').click(function() {
                var transaction_id = $(this).attr('id');
                checkTransactionStatus(transaction_id);
            })
        })
    </script>

@endpush
