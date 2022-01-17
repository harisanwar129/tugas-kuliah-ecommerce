@extends('layouts.front')

@section('content')
<div class="user-login-area mt-70 mb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="login-title text-center mb-30">
                    <h2>Reset Password</h2>
                    <p></p>
                </div>
            </div>
            <div class="offset-lg-3 col-lg-6 col-md-12 col-12">
                <form method="POST" action="{{ route('password.email') }}" class="login-form">
                    @csrf
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
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
                    <div class="single-login single-login-2 d-flex">
                        <button type="submit" class="btn btn-sqr">{{ __('messages.send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
