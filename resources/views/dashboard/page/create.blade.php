@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <form class="row myForm" id="myForm" method="POST" action="{{ route('dashboard.master.page.store') }}"
                    enctype="multipart/form-data">
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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nama Page <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
                        </div>
                    </div>
                    <div class="col-md-12 check-mobile">
                        <div class="form-group">
                            <label class="check-mobile-label">Konten</label>
                            <textarea name="konten" class="form-control check-mobile-form ckeditor"></textarea>
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
