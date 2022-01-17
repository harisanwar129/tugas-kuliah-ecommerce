@extends('layouts.front')

@section('content')
    <div class="user-login-area mt-70 mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="login-title text-center mb-30">
                        <h2>{{ __('messages.label_register') }}</h2>
                        <p>{{ __('messages.label_register_desc') }}</p>
                    </div>
                </div>
                <div class="offset-lg-2 col-lg-8 col-md-12 col-12">
                    <form action="{{ route('register') }}" method="POST" class="billing-fields">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-12">
                                <div class="single-register">
                                    <label>{{ __('messages.label_fullname') }}<span>*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control-front" />
                                    @if ($errors->has('name'))
                                        @foreach ($errors->get('name') as $msg)
                                            <small class="text-danger">{{ $msg }}</small>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="single-register">
                                    <label>Email<span>*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control-front" />
                                    @if ($errors->has('email'))
                                        @foreach ($errors->get('email') as $msg)
                                            <small class="text-danger">{{ $msg }}</small>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="single-register">
                                    <label>Handphone<span>*</span></label>
                                    <input type="number" name="phone" value="{{ old('phone') }}" class="form-control-front" />
                                    @if ($errors->has('phone'))
                                        @foreach ($errors->get('phone') as $msg)
                                            <small class="text-danger">{{ $msg }}</small>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="single-register">
                            <label>Username<span>*</span></label>
                            <input type="text" name="username" value="{{ old('username') }}" class="form-control-front" placeholder="Username" />
                            @if ($errors->has('username'))
                                @foreach ($errors->get('username') as $msg)
                                    <small class="text-danger">{{ $msg }}</small>
                                @endforeach
                            @endif
                        </div>
                        <div class="single-register">
                            <label>Password<span>*</span></label>
                            <input type="password" name="password" class="form-control-front" placeholder="Password" />
                            @if ($errors->has('password'))
                                @foreach ($errors->get('password') as $msg)
                                    <small class="text-danger">{{ $msg }}</small>
                                @endforeach
                            @endif
                        </div>
                        <div class="single-register">
                            <label>{{ __('messages.label_password_confirm') }}<span>*</span></label>
                            <input type="password" name="password_confirmation" class="form-control-front" placeholder="{{ __('messages.label_password_confirm') }}" />
                            @if ($errors->has('password_confirmation'))
                                @foreach ($errors->get('password_confirmation') as $msg)
                                    <small class="text-danger">{{ $msg }}</small>
                                @endforeach
                            @endif
                        </div>
                        <div class="single-register">
                          <input  class="btn btn-primary" type="reset" value="reset">
                            <button type="submit" class="btn btn-success">{{ __('messages.label_register') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
