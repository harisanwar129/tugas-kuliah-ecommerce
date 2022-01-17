
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ config('setting.logo_url') }}">

    <!-- all css here -->
    <!-- bootstrap v3.3.6 css -->
    <link rel="stylesheet" href="{{ asset('') }}/css/bootstrap.min.css">
    <!-- animate css -->
    <link rel="stylesheet" href="{{ asset('') }}/css/animate.css">
    <!-- meanmenu css -->
    <link rel="stylesheet" href="{{ asset('') }}/css/meanmenu.min.css">
    <!-- owl.carousel css -->
    <link rel="stylesheet" href="{{ asset('') }}/css/owl.carousel.css">
    <!-- font-awesome css -->
    <link rel="stylesheet" href="{{ asset('') }}/css/font-awesome.min.css">
    <!-- flexslider.css-->
    <link rel="stylesheet" href="{{ asset('') }}/css/flexslider.css">
    <!-- chosen.min.css-->
    <link rel="stylesheet" href="{{ asset('') }}/css/chosen.min.css">

    <!-- Notification css (Toastr) -->
    <link href="{{ asset('admin') }}/libs/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

    <!-- style css -->
    @include('components.style')
    <link rel="stylesheet" href="{{ asset('') }}/css/laravelstyle.css">
    <!-- responsive css -->
    <link rel="stylesheet" href="{{ asset('') }}/css/responsive.css">
    <!-- modernizr css -->
    <script src="{{ asset('') }}/js/vendor/modernizr-2.8.3.min.js"></script>

    <style>
        .onloading {
            background-color: #a9baef !important;
            cursor: no-drop !important;
        }

        .myaccount-tab-menu form,
        .myaccount-tab-menu form button {
            border: 1px solid #efefef !important;
            border-bottom: none !important;
            color: #222222 !important;
            font-weight: 400 !important;
            font-size: 15px !important;
            display: block !important;
            padding: 10px 15px !important;
            text-transform: capitalize !important;
        }

        .product-add-form form button, .product-add-to-cart form button, #add_to_cart {
            background: #333 none repeat scroll 0 0;
            border: 0 none;
            border-radius: 0;
            color: #fff;
            display: inline-block;
            font-size: 14px;
            font-weight: 700;
            height: 50px;
            line-height: 50px;
            padding: 0 28px;
            text-transform: uppercase;
            width: auto;
            transition: .3s;
        }

        .product-social-links form button {
            background: #000 none repeat scroll 0 0;
            color: #fff;
            display: inline-block;
            font-size: 19px;
            font-weight: 700;
            height: 45px;
            line-height: 45px;
            margin-right: 5px;
            padding: 0;
            text-align: center;
            text-decoration: none;
            width: 50px;
            transition: .3s;
        }


    </style>
