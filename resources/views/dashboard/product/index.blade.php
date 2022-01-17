@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="header-title">Produk Anda</h4>
                        <p class="sub-header">
                            Data produk dibawah ini akan tampil pada halaman depan aplikasi.</code>
                        </p>
                    </div>
                    <a href="{{ route('dashboard.master.product.create') }}"
                        class="btn btn-primary btn-rounded waves-effect waves-light"><i class="mdi mdi-plus"></i> Tambah</a>
                </div>

                <form action="" method="post">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="productCounter">0</span>
                            Produk dipilih.
                        </div>
                        <button type="button" data-toggle="modal" data-target="#massDeleteProductModal"
                                        class="btn btn-danger">
                                        Hapus <strong><span class="productCounter">0</span></strong>
                                        Produk
                                    </button>
                    </div>
                </form>

                <div class="table-responsive mt-2">
                    <table id="datatableku" class="table table-bordered dt-responsive"
                        style="border-collapse: collapse; border-spacing: 0; width: 100% !important;">
                        <thead>
                            <tr>
                                <th width="10">#</th>
                                <th width="10">No</th>
                                <th width="130">Foto</th>
                                <th width="40" style="max-width: 40px !important;">Nama Produk</th>
                                <th width="40">Stok</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th width="40">Harga</th>
                                <th width="40">Visible</th>
                                <th>Owner</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- end row -->

    <!-- Pesan Kelulusan Modal -->
    <div class="modal fade" id="massDeleteProductModal" tabindex="-1" role="dialog"
    aria-labelledby="massDeleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST"
            class="modal-content">
            @csrf
            <div class="modal-body">
                <div class="col-md-12">
                    <h5>Yakin menghapus produk?</h5>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary formSubmitter" id="massDeleteButton">Hapus</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
    <script>
        let selectedProduct = [];

        function toggleProduct(idProduct) {
            if (!$(`#check_${idProduct}`).attr('checked')) {
                $(`#check_${idProduct}`).attr('checked', true);
                selectedProduct.push(idProduct);
            } else {
                $(`#check_${idProduct}`).removeAttr('checked');
                selectedProduct = selectedProduct.filter(product => {
                    return product != idProduct
                })
            }

            $('.productCounter').html(selectedProduct.length)
        }

        $(document).on('click', '#massDeleteButton', function() {
            var the_data = {
                'products': selectedProduct,
            };

            if (selectedProduct.length === 0) {
                swal({
                    text: "Tidak ada yang terceklis",
                    type: "info",
                    timer: 1500
                });
            } else {
                // personil ada
                $(".formSubmitter").attr('disabled', true).addClass('disabled');
                $.ajax({
                    url: `{{ route('dashboard.master.product.mass_destroy') }}`,
                    dataType: 'json',
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: the_data,
                    success: function(response) {
                        $('.formSubmitter').removeClass('disabled').removeAttr('disabled');
                        console.log(response)
                        if (response.error == true) {
                            swal(
                                'Terjadi Kesalahan',
                                response.message,
                                'error',
                            );
                        } else {
                            if (response.data.has_sold_items === true) {
                                swal(
                                    'Terjadi Kesalahan',
                                    response.meta.message,
                                    'error',
                                );
                            } else {
                                swal(
                                    'Produk akan dihapus',
                                    response.meta.message,
                                    'info',
                                );
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);

                            }
                        }

                        $(".formSubmitter").attr('disabled', false).removeClass('disabled');

                    },
                    error: function(response) {
                        // console.log(response);
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
            }

        });

        $(function() {
            $('#datatableku').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                ajax: "{!! route('dashboard.master.product.index') !!}",
                orders: [0, "asc"],
                columns: [{
                        data: 'checklist',
                        name: 'checklist',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'thumbnail',
                        name: 'thumbnail'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    },
                    {
                        data: 'product_category.name',
                        name: 'product_category.name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'visible',
                        name: 'visible'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    },
                ]
            });
        });
    </script>
@endpush
