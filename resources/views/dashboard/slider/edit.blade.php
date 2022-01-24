@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <form class="row myForm" method="POST"
                    action="{{ route('dashboard.master.slider.update', $slider->id) }}" enctype="multipart/form-data">
                   
                    @csrf
                    @method('PUT')
                    <div class="form-group col-md-4">
                        <label>Title</label>
                        <input type="text" class="form-control" name="title" value="{{ $slider->title }}">
                        <small class="text-muted">Disarankan tidak lebih dari 20 Karakter</small>
                        <div>
                         @if ($errors->has('title'))
                                        @foreach ($errors->get('title') as $msg)
                                            <small class="text-danger">{{ $msg }}</small>
                                        @endforeach
                                    @endif
                                    </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Subtitle</label>
                        <input type="text" class="form-control" name="subtitle" value="{{ $slider->subtitle }}">
                        <small class="text-muted">Disarankan tidak lebih dari 30 Karakter</small>
                        <div>
                         @if ($errors->has('subtitle'))
                                        @foreach ($errors->get('subtitle') as $msg)
                                            <small class="text-danger">{{ $msg }}</small>
                                        @endforeach
                                    @endif
                                    </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Teks Tombol</label>
                        <input type="text" class="form-control" name="button_text" value="{{ $slider->button_text }}">
                        <small class="text-muted">Kosongkan jika tidak ingin memunculkan tombol</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Link Tombol</label>
                        <input type="text" class="form-control" name="link" value="{{ $slider->link }}">
                        <small class="text-muted">Sertakan full url: https://linkanda.com</small>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Urutan</label>
                        <input type="number" class="form-control" name="urutan" value="{{ $slider->urutan }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Thumbnail</label> <br>
                        <img src="{{ MyHelper::get_uploaded_file_url($slider->thumbnail) }}" alt="picture" width="150"
                            class="mb-1">
                        <input type="file" class="form-control" name="thumbnail">
                        <small class="text-muted">Disarankan ukuran: 1360 x 760</small>
                        <div>
                        @if ($errors->has('thumbnail'))
                            @foreach ($errors->get('thumbnail') as $msg)
                                <small class="text-danger">{{ $msg }}</small>
                            @endforeach
                        @endif
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Status</label>
                        <div class="checkbox checkbox-success checkbox-circle">
                            <input id="is_active" type="checkbox" {{ $slider->is_active == 1 ? 'checked' : '' }} value="1"
                                name="is_active">
                            <label for="is_active">
                                Visible
                            </label>
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
