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
                            <img src="{{ asset('img/logo.png') }}" alt="Logo Aplikasi" width="200">
                            {{-- <h4 class="text-uppercase mt-0">{{ config('app.name') }}</h4> --}}
                        </div>
                        <div class="float-sm-right mt-4 mt-sm-0">
                            <h5>Invoice # {{ $transaction->trx_id ?? '' }} <br>
                                <small>{{ $transaction->sid }}</small>
                                <br>
                                @if ($transaction->payment_url)
                                    <a class="lead" target="_blank" href="{{ $transaction->payment_url }}">Link
                                        Pembayaran</a>
                                @endif
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
                            </div>
                        </div><!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row mt-4">
                        <div class="col-12">
                            <p class="lead blockquote-footer" style="font-size: 16px">
                                Jika latar belakang baris produk pada tabel berwarna merah atau tulisan dicoret artinya produk itu milik toko lain, sehingga tidak dihitung kedalam pemasukan toko anda.
                            </p>
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
                                        @endphp
                                        @foreach ($transaction->transaction_items as $item)
                                            {{-- artinya produk ini bukan milik seller yang sedang login, maka exclude subtotal --}}
                                            @php
                                                if ($item->product && (Auth::user()->role == 'SELLER' && Auth::user()->id != $item->product->user_id)) {
                                                    $class = 'table-danger';
                                                    $exclude = true;
                                                } else {
                                                    $class = '';
                                                    $exclude = false;
                                                }
                                            @endphp
                                            <tr class="{{ $class }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{!! $exclude ? '<strike>' : '' !!}{{ (isset($item->product)) ? $item->product->name : '' }}</td>
                                                <td>{!! $exclude ? '<strike>' : '' !!}{{ (isset($item->product)) ? $item->product->description : '' }}</td>
                                                <td>{!! $exclude ? '<strike>' : '' !!}{{ $item->quantity }}</td>
                                                <td>{!! $exclude ? '<strike>' : '' !!}Rp {{ MyHelper::rupiah($item->price) }}</td>
                                                <td>{!! $exclude ? '<strike>' : '' !!}Rp {{ MyHelper::rupiah($item->subtotal) }}</td>
                                            </tr>
                                            @php
                                                if (!$exclude) {
                                                    $grand_total += $item->subtotal;
                                                }
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="clearfix mt-5">
                                <h5 class="small"><b>SYARAT DAN KEBIJAKAN PEMBAYARAN</b></h5>

                                <small class="text-muted">
                                    Semua akun harus dibayar dalam waktu 7 hari sejak diterimanya
                                    faktur. Harus dibayar dengan cek atau kartu kredit atau pembayaran langsung
                                    on line. Jika akun tidak dibayar dalam 7 hari rincian kredit
                                    diberikan sebagai konfirmasi pekerjaan yang dilakukan akan dikenakan biaya:
                                    disepakati biaya dikutip disebutkan di atas.
                                </small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-right mt-4">
                                <h3>Rp {{ MyHelper::rupiah($grand_total) }}</h3>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-print-none">
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
                    console.log(res)
                    if (res.data.Data.Status == 1) {
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
                    } else if (res.data.Data.Status == 0) {
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
                    } else if (res.data.Data.Status == 2) {
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
