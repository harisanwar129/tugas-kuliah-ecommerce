@extends('layouts.admin')

@push('css')
<link href="{{ asset('') }}/admin/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('') }}/admin/libs/clockpicker/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="text-center">
                    <h3 class="mb-4">{{ $title ?? config('app.name') }}</h3>
                    <p class="text-muted">
                        Silahkan isi identitas aplikasi anda.
                    </p>
                </div>

                <form class="row myForm" method="POST" action="{{ route('dashboard.setting') }}"
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
                    @method('PUT')
                    <div class="form-group col-md-3">
                        <label>Nama Aplikasi</label>
                        <input type="text" class="form-control" name="app_name" id="app_name" value="{{ $setting->app_name }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Logo (Rounded)</label>
                        @if ($setting->logo != '' || $setting->logo != NULL)
                        <br><img src="{{ MyHelper::get_uploaded_file_url($setting->logo) }}" alt="picture" width="150" class="mb-3">
                        @endif
                        <input type="file" class="form-control" name="logo">
                        <small class="text-muted">Support: PNG,JPG</small>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Logo (Rectangle)</label>
                        @if ($setting->logo_rect != '' || $setting->logo_rect != NULL)
                        <br><img src="{{ MyHelper::get_uploaded_file_url($setting->logo_rect) }}" alt="picture" width="150" class="mb-3">
                        @endif
                        <input type="file" class="form-control" name="logo_rect">
                        <small class="text-muted">Support: PNG,JPG</small>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Warna Tema</label>
                        <input type="text" name="color" id="default-colorpicker" class="form-control" value="{{ $setting->color }}">
                    </div>
                    <div class="form-group col-md-12">
                        <label>Teks Footer</label>
                        <input type="text" class="form-control" name="footer_text" id="footer_text" value="{{ $setting->footer_text }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Provinsi Pengiriman {{ $setting->province }}</label>
                        <select name="province_from" id="province_from" class="form-control">
                            <option value="">{{ __('messages.choose') }}</option>
                            @foreach ($provinces as $item)
                                <option value="{{ $item->province_id }}" {{ (isset($setting->city->province) && $setting->city->province->province_id == $item->province_id) ? 'selected' : '' }}>{{ $item->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Kota Pengiriman</label>
                        <select name="city_from" id="city_from" class="form-control">
                            @if ($cities != null)
                                @foreach ($cities as $item)
                                    <option value="{{ $item->city_id }}" {{ $item->city_id == $setting->city_id ? 'selected' : '' }}>{{ $item->title }}</option>
                                @endforeach
                            @else
                            <option value="">{{ __('messages.choose') }}</option>
                            <option value="">Pilih Provinsi Dulu</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <hr>
                    </div>
                    <div class="form-group col-md-3">
                        <label>iPaymu API Key</label>
                        <input type="text" class="form-control" name="ipaymu_api" id="ipaymu_api" value="{{ $setting->ipaymu_api }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>iPaymu VA</label>
                        <input type="text" class="form-control" name="ipaymu_va" id="ipaymu_va" value="{{ $setting->ipaymu_va }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>iPaymu URL</label>
                        <input type="text" class="form-control" name="ipaymu_url" id="ipaymu_url" value="{{ $setting->ipaymu_url }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label>Kode Rahasia <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="kode_rahasia" id="kode_rahasia" required value="" autocomplete="off">
                        <small class="text-muted">Untuk mengubah setting, masukkan kode rahasia (tanya admin)</small>
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
        <script src="{{ asset('') }}/admin/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
        <script src="{{ asset('') }}/admin/libs/clockpicker/bootstrap-clockpicker.min.js"></script>

        <!-- Init js-->
        <script src="{{ asset('') }}/admin/js/pages/form-pickers.init.js"></script>
    <script>
        $(document).ready(function() {
            $('#name').on('keyup', function() {
                $('#slugPreview').html('{{ url('') }}/<b><u>' + string_to_slug($(this).val()) + '</u></b>');
            })

            $('#province_from').on('change', function() {
                let province_from = $(this).val();
                if (province_from != "") {
                    $.ajax({
                        url: `{{ url('city/fetch') }}/${province_from}`,
                        success: function(response) {
                            let initialContent =
                                `<option selected value="">-Silahkan Pilih-</option>`
                            let newOptions = ``

                            response.forEach(city => {
                                newOptions +=
                                    `<option value="${city.city_id}">${city.title}</option>`
                            })

                            $("#city_from").html(initialContent + newOptions).val("")
                            checkElement()
                        },
                        error: function() {
                            swal("Error!", "Telah terjadi kesalahan pada server!", {
                                icon: "error",
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-danger'
                                    }
                                },
                            });
                        },
                        timeout: function() {
                            swal("Error!", "Timeout!", {
                                icon: "error",
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-danger'
                                    }
                                },
                            });
                        }
                    })
                } else {
                    $("#city_from").html("")
                }
            })
        })
    </script>
@endpush
