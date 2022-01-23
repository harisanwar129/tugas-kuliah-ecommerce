@extends('layouts.front')



@section('content')
    <!-- entry-header-area-start -->
    <div class="entry-header-area mt-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="entry-header-title">
                        <h2>{{ $title ?? config('app.name') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- entry-header-area-end -->
    <!-- my account wrapper start -->
    <div class="my-account-wrapper mb-70">
        <div class="container">
            <div class="section-bg-color">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- My Account Page Start -->
                        <div class="myaccount-page-wrapper">
                            <!-- My Account Tab Menu Start -->
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <div class="myaccount-tab-menu nav" role="tablist">
                                        <a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i>
                                            Dashboard</a>
                                        <a href="{{ route('front.pesanan_saya') }}"><i class="fa fa-cart-arrow-down"></i>
                                            {{ __('messages.my_orders') }}</a>
                                        <a href="{{ route('front.my_profile') }}" class="active"><i
                                                class="fa fa-user"></i> {{ __('messages.my_profile') }}</a>
                                        <form action="{{ route('logout') }}" method="POST"
                                            style="display:flex !important; justify-content:flex-end">
                                            @csrf
                                            <button type="submit" class="buttonLogout"><i class="fa fa-sign-out"></i> Logout</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="col-lg-9 col-md-8">
                                    <div class="tab-content" id="myaccountContent">

                                        <!-- Single Tab Content Start -->
                                        <div class="tab-pane fade show active" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h5>Profile Saya</h5>
                                                <div class="account-details-form">
                                                    <form action="{{ route('front.my_profile.update') }}" method="POST"
                                                        enctype="multipart/form-data" class="myForm">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="username"
                                                                        class="required">Username</label>
                                                                    <input type="text" name="username"
                                                                        value="{{ $user->username }}" id="username"
                                                                        placeholder="Username" />
                                                                        @if ($errors->has('username'))
                                                                            @foreach ($errors->get('username') as $msg)
                                                                                <small class="text-danger">{{ $msg }}</small>
                                                                            @endforeach
                                                                        @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="email" class="required">Email</label>
                                                                    <input type="email" name="email"
                                                                        value="{{ $user->email }}" id="email"
                                                                        placeholder="Email" />
                                                                        @if ($errors->has('email'))
                                                                            @foreach ($errors->get('email') as $msg)
                                                                                <small class="text-danger">{{ $msg }}</small>
                                                                            @endforeach
                                                                        @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="single-input-item">
                                                            <label for="name" class="required">{{ __('messages.label_fullname') }}</label>
                                                            <input type="text" name="name" value="{{ $user->name }}"
                                                                id="name" placeholder="{{ __('messages.label_fullname') }}" />
                                                            @if ($errors->has('name'))
                                                                @foreach ($errors->get('name') as $msg)
                                                                    <small class="text-danger">{{ $msg }}</small>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="single-input-item">
                                                            <label for="address" class="required">{{ __('messages.label_address') }}</label>
                                                            <textarea name="address" id="address" cols="30"
                                                                rows="4">{{ $user->address }}</textarea>
                                                            @if ($errors->has('address'))
                                                                @foreach ($errors->get('address') as $msg)
                                                                    <small class="text-danger">{{ $msg }}</small>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="phone" min="0" class="required">Handphone</label>
                                                                    <input type="number" name="phone"
                                                                        value="{{ $user->phone }}" id="phone"
                                                                        placeholder="Handphone" />
                                                                    @if ($errors->has('phone'))
                                                                        @foreach ($errors->get('phone') as $msg)
                                                                            <small class="text-danger">{{ $msg }}</small>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="avatar" class="required">{{ __('messages.label_photo') }}</label>
                                                                    <br>
                                                                    <img src="{{ MyHelper::get_uploaded_file_url($user->avatar) }}"
                                                                        alt="picture" width="150" class="mb-1">
                                                                    <input type="file" name="avatar" id="avatar" />
                                                                    @if ($errors->has('avatar'))
                                                                        @foreach ($errors->get('avatar') as $msg)
                                                                            <small class="text-danger">{{ $msg }}</small>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <fieldset>
                                                            <legend>Update Password <small><i>(Optional)</i></small>
                                                            </legend>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="single-input-item">
                                                                        <label for="new-pwd" class="required">{{ __('messages.label_password_new') }}</label>
                                                                        <input type="password" name="password" id="new-pwd"
                                                                            placeholder="{{ __('messages.label_password_new') }}" />
                                                                        @if ($errors->has('password'))
                                                                            @foreach ($errors->get('password') as $msg)
                                                                                <small class="text-danger">{{ $msg }}</small>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="single-input-item">
                                                                        <label for="confirm-pwd"
                                                                            class="required">{{ __('messages.label_password_confirm') }}</label>
                                                                        <input type="password" name="password_confirmation"
                                                                            id="confirm-pwd"
                                                                            placeholder="{{ __('messages.label_password_confirm') }}" />
                                                                            @if ($errors->has('password_confirmation'))
                                                                                @foreach ($errors->get('password_confirmation') as $msg)
                                                                                    <small class="text-danger">{{ $msg }}</small>
                                                                                @endforeach
                                                                            @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <div class="single-input-item">
                                                            <button class="btn btn-sqr btn-danger" type="reset">Reset</button>
                                                            <button class="btn btn-sqr formSubmitter btn-primary">{{ __('messages.label_save') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div> <!-- Single Tab Content End -->
                                    </div>
                                </div>
                            </div>
                        </div> <!-- My Account Page End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- my account wrapper end -->
@endsection
