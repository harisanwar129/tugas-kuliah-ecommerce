@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h4 class="header-title">{{ $title ?? config('app.name') }}</h4>
                    </div>
                    <a href="{{ route('dashboard.master.page.create') }}" class="btn btn-primary btn-rounded waves-effect waves-light"><i class="mdi mdi-plus"></i> Tambah</a>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Halaman</th>
                            <th>Slug</th>
                            <th width="40">Aksi</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($page as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->slug }}</td>
                                <td>
                                    <div class="d-flex justify-content-start">
                                        <a href="{{ route('page.show', $item->slug) }}" class="btn btn-sm btn-primary mr-2" target="_blank">Preview</a>
                                        <a href="{{ route('dashboard.master.page.edit', $item->id) }}" class="btn btn-sm waves-effect waves-light btn-warning mr-1"><i class="mdi mdi-wrench"></i></a>
                                        <form action="{{ route('dashboard.master.page.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm waves-effect waves-light btn-danger buttonDeletion"><i class="mdi mdi-trash-can"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end row -->
@endsection
