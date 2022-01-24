@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <form class="row myForm" id="myForm" method="POST"
                    action="{{ route('dashboard.master.page.update', $page->id) }}" enctype="multipart/form-data">
                    
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nama Page <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" value="{{ $page->nama }}">
                             <div>
                           @if ($errors->has('nama'))
                                        @foreach ($errors->get('nama') as $msg)
                                            <small class="text-danger">{{ $msg }}</small>
                                        @endforeach
                                    @endif
                                    </div>
                        </div>
                    </div>
                    <div class="col-md-12 check-mobile">
                        <div class="form-group">
                            <label class="check-mobile-label">Konten</label>
                            <textarea name="konten" class="form-control check-mobile-form ckeditor">{!! $page->konten !!}</textarea>
                             <div>
                           @if ($errors->has('konten'))
                                        @foreach ($errors->get('konten') as $msg)
                                            <small class="text-danger">{{ $msg }}</small>
                                        @endforeach
                                    @endif
                                    </div>
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
    <!-- CKEditor -->
    <script src="{{ asset('') }}/admin/js/ckeditor/ckeditor.js"></script>
@endpush
