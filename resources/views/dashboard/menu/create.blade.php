@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <form class="row myForm" id="myForm" method="POST"
                    action="{{ route('dashboard.master.menu.store') }}" enctype="multipart/form-data">
                    
                    @csrf
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nama Menu</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
                               <div>
                           @if ($errors->has('nama'))
                                        @foreach ($errors->get('nama') as $msg)
                                            <small class="text-danger">{{ $msg }}</small>
                                        @endforeach
                                    @endif
                                    </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Link</label>
                            <input type="text" name="link" class="form-control" value="{{ old('link') }}">
                            <div>
                           @if ($errors->has('link'))
                                        @foreach ($errors->get('link') as $msg)
                                            <small class="text-danger">{{ $msg }}</small>
                                        @endforeach
                                    @endif
                                    </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Urutan</label>
                            <input type="number" name="urutan" min="1" class="form-control" value="{{ old('urutan') }}">
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
                      <input  class="btn btn-primary" type="reset" value="reset">
                        <button type="submit" class="btn btn-success formSubmitter">Simpan</button>
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
