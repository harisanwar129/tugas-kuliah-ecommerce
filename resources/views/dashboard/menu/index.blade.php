@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h4 class="header-title">{{ $title ?? config('app.name') }}</h4>
                    </div>
                    <a href="{{ route('dashboard.master.menu.create') }}" class="btn btn-primary btn-rounded waves-effect waves-light"><i class="mdi mdi-plus"></i> Tambah</a>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Menu</th>
                            <th>Has Sub Menu?</th>
                            <th>Link</th>
                            <th>Urutan</th>
                            <th>Visible</th>
                            <th width="40">Aksi</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($menu as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $item->nama }} <br>
                                    <div class="badge badge-info">{{ $item->position }}</div>
                                </td>
                                <td>
                                    @if ($item->sub_menus->count() > 0)
                                    <ul>
                                        @foreach ($item->sub_menus as $sub)
                                            <li>{{ $sub->nama }}</li>
                                        @endforeach
                                    </ul>
                                    @else
                                        No
                                    @endif
                                </td>
                                <td>{{ $item->link }}</td>
                                <td>{{ $item->urutan }}</td>
                                <td>{{ $item->visible == 1 ? 'Yes' : 'No' }}</td>
                                <td>
                                    <div class="d-flex justify-content-start">
                                        <a href="{{ route('dashboard.master.menu.edit', $item->id) }}" class="btn btn-sm waves-effect waves-light btn-warning mr-1"><i class="mdi mdi-wrench"></i></a>
                                        <form action="{{ route('dashboard.master.menu.destroy', $item->id) }}" method="POST">
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
