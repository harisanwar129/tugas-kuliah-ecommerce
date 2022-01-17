@extends('layouts.front')

@section('content')
    <!-- user-login-area-start -->
    <div class="user-login-area mt-70 mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="login-title text-center mb-30">
                        <h2>Login</h2>
                        <p>{{ __('messages.label_login_desc') }}</p>
                    </div>
                </div>
                <div class="offset-lg-3 col-lg-6 col-md-12 col-12">
                    <form method="POST" action="{{ route('login') }}" class="login-form">
                        @csrf

                        <div class="single-login">
                            <label>Username<span>*</span></label>
                            <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus />
                            @if ($errors->has('username'))
                                    @foreach ($errors->get('username') as $msg)
                                        <small class="text-danger">{{ $msg }}</small>
                                    @endforeach
                                @endif
                        </div>
                        <div class="single-login">
                            <label>Password<span>*</span></label>
                            <input type="password" name="password" id="password" />
                            @if ($errors->has('password'))
                                    @foreach ($errors->get('password') as $msg)
                                        <small class="text-danger">{{ $msg }}</small>
                                    @endforeach
                                @endif
                        </div>
                        <div class="single-login single-login-2 d-flex">
                         <button  class="btn btn-primary" type="reset" value="reset" style=" margin-left: 10px;">Reset</button>
                            <button type="submit" class="btn btn-success" style=" margin-left: 10px;">login</button>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">{{ __('messages.label_forgot_password') }}</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- user-login-area-end -->
@endsection
