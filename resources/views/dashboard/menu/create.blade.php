@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <form class="row myForm" id="myForm" method="POST"
                    action="{{ route('dashboard.master.menu.store') }}" enctype="multipart/form-data">
                    @if ($errors->any())
                        <div class="col-md-12">
                            <ul>
                                @foreach ($errors->all() as $item)
                                    <li class="text-danger">
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @csrf
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nama Menu</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Link</label>
                            <input type="text" name="link" class="form-control" value="{{ old('link') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Urutan</label>
                            <input type="number" name="urutan" class="form-control" value="{{ old('urutan') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Posisi</label>
                            <select name="position" id="position" class="form-control">
                                <option value="header">Header</option>
                                <option value="footer">Footer</option>
                                <option value="header_footer">Header & Footer</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tampil ?</label>
                            <p class="demo">
                                <input type="checkbox" name="visible" value="1" checked
                                    data-toggle="toggle" data-onstyle="success" data-style="btn-round">
                            </p>
                        </div>
                    </div>

                    <div class="form-group col-md-12 text-right">
                        <button type="submit" class="btn btn-primary formSubmitter">Simpan</button>
                    </div>
                </form>
            </div>
        </div><!-- end col -->
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {

        })
    </script>
@endpush