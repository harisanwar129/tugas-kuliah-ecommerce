@extends('layouts.front')

@section('content')
<div class="user-login-area mt-70 mb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="login-title text-center mb-30">
                    <h2>Login</h2>
                    <p>Silahkan login untuk masuk ke dalam aplikasi</p>
                </div>
            </div>
            <div class="offset-lg-3 col-lg-6 col-md-12 col-12">
                <form method="POST" action="{{ route('password.update') }}" class="login-form">
                    @csrf
                    @if ($errors->any())
                        <div class="card-body">
                            <ul>
                                @foreach ($errors->all() as $item)
                                    <li class="text-danger">
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="single-login">
                        <label>Email<span>*</span></label>
                        <input type="email" name="email" id="email" :value="old('email')" required autofocus />
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="single-login">
                        <label>Password<span>*</span></label>
                        <input type="password" name="password" id="password" />
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="single-login single-login-2 d-flex">
                        <button type="submit" class="btn btn-sqr">login</button>
                        <input id="rememberme" type="checkbox" name="remember" value="forever">
                        <span>Remember me</span>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Lost your password?</a>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
