@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="table-responsive">
                    <table id="datatableku" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th width="10">#</th>
                                <th width="120">Tanggal | Jam</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Total</th>
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
@endsection

@push('js')
    <script>
        $(function() {
            $('#datatableku').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                ajax: "{!! route('dashboard.histori_penjualan') !!}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'subtotal',
                        name: 'subtotal'
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
