@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="header-title">Slider Beranda Website</h4>
                        <p class="sub-header">
                            Data Slider dibawah ini akan muncul pada halaman beranda aplikasi</code>.
                        </p>
                    </div>
                    <a href="{{ route('dashboard.master.slider.create') }}" class="btn btn-primary btn-rounded waves-effect waves-light"><i class="mdi mdi-plus"></i> Tambah</a>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Gambar</th>
                            <th>Teks Tombol</th>
                            <th>Link Tombol</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($slider as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->subtitle }}</td>
                                <td>
                                    <img src="{{ MyHelper::get_uploaded_file_url($item->thumbnail) }}" alt="picture" width="120">
                                </td>
                                <td>
                                    {{ $item->button_text }}
                                </td>
                                <td>
                                    <a href="{{ $item->link }}" target="_blank">
                                        {{ $item->link }}
                                    </a>
                                </td>
                                <td>
                                    {!! MyHelper::get_visibility_status($item->is_active) !!}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-start">
                                        <a href="{{ route('dashboard.master.slider.edit', $item->id) }}" class="btn btn-sm waves-effect waves-light btn-warning mr-1"><i class="mdi mdi-wrench"></i></a>
                                        <form action="{{ route('dashboard.master.slider.destroy', $item->id) }}" method="POST">
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
