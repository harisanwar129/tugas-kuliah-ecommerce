@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="header-title">List Akun</h4>
                        <p class="sub-header">
                            List akun yang terdaftar di dalam aplikasi.</code>
                        </p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatableku" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th width="10">#</th>
                                <th width="40">Foto</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Nomor HP</th>
                                <th>Email</th>
                                <th width="40">Role</th>
                                <th width="30">Produk Dimiliki</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- end row -->
@endsection

@push('js')
    <script>
        $(function() {
            $('#datatableku').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                ajax: "{!! route('dashboard.master.user.index') !!}",
                orders: [0, "asc"],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'avatar',
                        name: 'avatar'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'products_count',
                        name: 'products_count'
                    },
                ]
            });
        });
    </script>
@endpush
