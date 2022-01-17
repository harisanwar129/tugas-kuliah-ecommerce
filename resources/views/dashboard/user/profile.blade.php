@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <form class="row myForm" method="POST" action="{{ route('dashboard.master.user.update_profile') }}"
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
                    <div class="form-group col-md-4">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" id="username" value="{{ $user->username }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>{{ __('messages.label_fullname') }}</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Handphone</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="{{ $user->phone }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Avatar</label>
                        @if (Auth::user()->avatar != '' || Auth::user()->avatar != NULL)
                        <br><img src="{{ MyHelper::get_uploaded_file_url($user->avatar) }}" alt="picture" width="150" class="mb-3">
                        @endif
                        <input type="file" class="form-control" name="avatar">
                        <small class="text-muted">Support: PNG,JPG</small>
                    </div>

                    <div class="form-group col-md-12 text-right">
                        <button type="submit" class="btn btn-primary formSubmitter">{{ __('messages.label_save') }}</button>
                    </div>
                </form>
            </div>
        </div><!-- end col -->
    </div>
@endsection

@push('js')

@endpush
