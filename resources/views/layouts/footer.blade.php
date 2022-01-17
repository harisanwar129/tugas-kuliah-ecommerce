<!-- footer-area-start -->
<footer>
    <!-- footer-mid-start -->
    <div class="footer-mid ptb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="row">
                        @php
                            $menu = \App\Menu::where('visible', 1)
                                ->whereIn('position', ['footer', 'header_footer'])
                                ->orderBy('urutan', 'ASC')
                                ->get();
                        @endphp
                        @foreach ($menu as $item)
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="single-footer br-2 xs-mb">
                            @if ($item->sub_menus->count() > 0)
                                <div class="footer-title mb-20">
                                    <h3>{{ $item->nama }}</h3>
                                </div>
                                <div class="footer-mid-menu">
                                    <ul>
                                        @foreach ($item->sub_menus as $sub)
                                            <li><a
                                                    href="{{ $sub->get_link() }}">{{ $sub->nama }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <div class="footer-title mb-20">
                                    <h3><a href="{{ $item->link }}">{{ $item->nama }}</a></h3>
                                </div>
                            @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- footer-mid-end -->
    <!-- footer-bottom-start -->
    <div class="footer-bottom bg-white">
        <div class="container">
            <div class="row bt-2">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="copy-right-area">
                        <p style="color: black;">{{ config('setting.footer_text') }} </strong></p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="payment-img text-right">
                        <a href="#"><img src="https://my.ipaymu.com/asset/images/qris-app.png" alt="payment" /></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- footer-bottom-end -->
</footer>
<!-- footer-area-end -->



<!-- all js here -->
<!-- jquery latest version -->
<script src="{{ asset('/') }}/js/vendor/jquery-1.12.4.min.js"></script>
<!-- popper js -->
<script src="{{ asset('/') }}/js/popper.min.js"></script>
<!-- bootstrap js -->
<script src="{{ asset('/') }}/js/bootstrap.min.js"></script>
<!-- owl.carousel js -->
<script src="{{ asset('/') }}/js/owl.carousel.min.js"></script>
<!-- meanmenu js -->
<script src="{{ asset('/') }}/js/jquery.meanmenu.js"></script>
<!-- wow js -->
<script src="{{ asset('/') }}/js/wow.min.js"></script>
<!-- jquery.parallax-1.1.3.js -->
<script src="{{ asset('/') }}/js/jquery.parallax-1.1.3.js"></script>
<!-- jquery.countdown.min.js -->
<script src="{{ asset('/') }}/js/jquery.countdown.min.js"></script>
<!-- jquery.flexslider.js -->
<script src="{{ asset('/') }}/js/jquery.flexslider.js"></script>
<!-- chosen.jquery.min.js -->
<script src="{{ asset('/') }}/js/chosen.jquery.min.js"></script>
<!-- jquery.counterup.min.js -->
<script src="{{ asset('/') }}/js/jquery.counterup.min.js"></script>
<!-- waypoints.min.js -->
<script src="{{ asset('/') }}/js/waypoints.min.js"></script>

<!-- Sweet Alert -->
<script src="{{ asset('admin/js/sweetalert/sweetalert.min.js') }}"></script>

<!-- Toastr js -->
<script src="{{ asset('admin') }}/libs/toastr/toastr.min.js"></script>

<script src="{{ asset('admin') }}/js/pages/toastr.init.js"></script>

<script src="{{ asset('admin') }}/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>

<!-- plugins js -->
<script src="{{ asset('/') }}/js/plugins.js"></script>
<!-- main js -->
<script src="{{ asset('/') }}/js/main.js"></script>

@stack('js')

@if (Auth::check())
    <script>
        var user_id = '{{ auth()->user()->id }}'
    </script>
@else
    <script>
        var user_id = ''
    </script>
@endif

<script>
    function trim(str) {
        return str.toString().replace(/^\s+|\s+$/g, '');
    }

    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    function reCalculateTotal(user_id) {
        $.ajax({
            url: "{{ route('v1.recalculate_total') }}",
            method: "POST",
            type: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                user_id: user_id
            },
            success: function(res) {
                $('#total_amount').text('Rp ' + res.data.total);
                $('#cart_items_total').text('Rp ' + res.data.total);
            },
            error: function(res) {
                console.log('error');
                return 'something goes wrong';
            }
        });
    }

    function reloadCartItems(user_id) {
        $.ajax({
            url: "{{ route('v1.reload_cart_items') }}",
            method: "POST",
            type: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                user_id: user_id
            },
            success: function(res) {
                $('#cart_items_count').text(res.data.items.length);
                var reloadedItems = ``;
                res.data.items.forEach(item => {
                    var productPrice = formatRupiah(trim(item.price));
                    reloadedItems += `
                    <div class="single-cart floating-cart-item" id="${item.id}">
                            <div class="cart-img">
                                <a href="${item.product.detail_url}"><img src="${item.product.thumbnail_complete_url}" alt="book" /></a>
                            </div>
                            <div class="cart-info">
                                <h5><a href="${item.product.detail_url}">${item.product.short_name}</a></h5>
                                <p>${item.quantity} x ${productPrice}</p>
                            </div>
                            <div class="cart-icon">
                                <a href="javascript:void(0)" class="floating_delete_cart" id="${item.id}"><i class="fa fa-remove"></i></a>
                            </div>
                        </div>
                    `;
                });
                $('#cart-product-content').html(reloadedItems);
                reCalculateTotal(user_id);
            },
            error: function(res) {
                console.log('error');
            }
        });
    }


    $(document).ready(function() {

        $(".myForm").on('submit', function(event) {
            $(".formSubmitter").attr('disabled', true).addClass('onloading');
            $(".myForm").attr('onsubmit', 'return false');
        });

        $(document).on('click', '.action-view', function() {
            var productId = $(this).attr('id');
            $.ajax({
                url: "{{ route('v1.fetch_detail_product') }}",
                method: "POST",
                type: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId
                },
                success: function(res) {
                    var productWeight = formatRupiah(trim(res.data.weight.toString()));
                    console.log(productWeight);
                    if (res.data.is_discount) {
                        var formattedPrice = formatRupiah(trim(res.data.price));
                        var formattedStrikedPrice = formatRupiah(trim(res.data.price_striked));
                        $('#productPrice').html(`
                            <span>Rp ${formattedStrikedPrice}</span>
                            <span class="old-price">Rp ${formattedPrice}</span>
                        `);
                    } else {
                        var formattedPrice = formatRupiah(trim(res.data.price));
                        $('#productPrice').html(`
                            <span>Rp ${formattedPrice}</span>
                        `);
                    }
                    $('#formProductId').val(productId);
                    $('#productName').text(res.data.name);
                    $('#productWeight').text(productWeight);
                    $('#productDescription').text(res.data.description);
                    if (res.data.stock > 0) {
                        $('#stock_status').html(`<i class="fa fa-check"></i> {{ __('messages.stock') }}: ${res.data.stock}`);
                    } else {
                        $('#stock_status').html(`<i class="fa fa-close" style="color:red"></i> {{ __('messages.stock') }}: 0`);
                    }
                    var i = 1;
                    var productDetailImages = `<div class="tab-pane active" id="image-${i}">
                                        <img src="${res.data.thumbnail_complete_url}" alt="" />
                                    </div>
                                    `;
                    var productDetailCarouselImages = `<a class="active" href="#image-${i}">
                                                    <img src="${res.data.thumbnail_complete_url}" alt="" /></a>
                                                    `;

                    for (var j = 0; j < res.data.product_galleries.length; j++) {
                        i++;
                        productDetailImages += `<div class="tab-pane" id="image-${i}">
                                        <img src="${res.data.product_galleries[j].thumbnail_complete_url}" alt="" />
                                    </div>
                                    `;
                        productDetailCarouselImages +=
                            `<a href="#image-${i}">
                                        <img src="${res.data.product_galleries[j].thumbnail_complete_url}" alt="" /></a>
                                        `;
                    }

                    $('#productDetailImages').html(productDetailImages);
                    $('#productDetailCarouselImages').html("");
                    if (res.data.product_galleries.length > 0) {
                        $('#productDetailCarouselImages').html(productDetailCarouselImages);
                        var $owl = $('#productDetailCarouselImages');
                        $owl.trigger('destroy.owl.carousel');

                        $owl.html($owl.find('.owl-stage-outer').html()).removeClass(
                            'owl-loaded');
                        $owl.owlCarousel({
                            loop: true,
                            autoplay: false,
                            autoplayTimeout: 5000,
                            navText: ['<i class="fa fa-angle-left"></i>',
                                '<i class="fa fa-angle-right"></i>'
                            ],
                            nav: true,
                            items: res.data.product_galleries.length,
                            margin: 12,
                        });

                        var ProductDetailsSmall = $('.product-details-small a');
                        ProductDetailsSmall.on('click', function(e) {
                            e.preventDefault();
                            var $href = $(this).attr('href');
                            ProductDetailsSmall.removeClass('active');
                            $(this).addClass('active');
                            $('.product-details-large .tab-pane').removeClass(
                                'active');
                            $('.product-details-large ' + $href).addClass('active');
                        })
                    }
                    $('#quantity').val(1);
                    $('#productModal').modal('show');
                },
                error: function(res) {
                    console.log('error');
                    console.log(res);
                }
            });
        });

        $(document).on('click', '.buttonTerimaPesanan', function(e) {
            e.preventDefault();
            let form = $(this).parents('form');

            swal({
                title: 'Apakah Anda yakin menerima pesanan ini?',
                text: "Status pengiriman akan diubah menjadi Diterima",
                icon: 'warning',
                buttons: {
                    confirm: {
                        text: 'Ya!',
                        className: 'btn btn-success'
                    },
                    cancel: {
                        visible: true,
                        text: 'Batal',
                        className: 'btn btn-danger'
                    }
                }
            }).then((Delete) => {
                if (Delete) {
                    form.submit()
                    swal({
                        title: 'Mohon tunggu proses!',
                        text: 'mohon tunggu.',
                        icon: 'info',
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        }
                    });
                } else {
                    swal.close();
                }
            });
        });

        $(document).on('click', '.buttonLogout', function(e) {
                    e.preventDefault();
                    let form = $(this).parents('form');

                    swal({
                        title: 'Apakah Anda yakin ingin logout?',
                        text: "Sesi akan berakhir",
                        icon: 'warning',
                        buttons: {
                            confirm: {
                                text: 'Ya!',
                                className: 'btn btn-success'
                            },
                            cancel: {
                                visible: true,
                                text: 'Batal',
                                className: 'btn btn-danger'
                            }
                        }
                    }).then((Delete) => {
                        if (Delete) {
                            form.submit()
                            swal({
                                title: 'Mohon tunggu proses!',
                                text: 'mohon tunggu.',
                                icon: 'info',
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-success'
                                    }
                                }
                            });
                        } else {
                            swal.close();
                        }
                    });
                });

        $("form#product_form").submit(function(event) {
            event.preventDefault();
            var product_id = $("#formProductId").val();
            var quantity = $("#quantity").val();
            var the_data = {
                product_id: product_id,
                quantity: quantity,
                user_id: user_id,
            }
            $.ajax({
                type: "POST",
                url: "{{ route('v1.add_to_cart') }}",
                data: the_data,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    console.log(res)
                    if (res.data.is_success == true) {
                        if (res.data.stock_empty == true) {
                            const el = document.createElement('div')
                            el.innerHTML =
                                "{{ __('messages.stock') . ' ' . __('messages.not') . ' ' . __('messages.availibility') }}"
                            swal({
                                title: '{{ __('messages.stock') . ' ' . __('messages.not') . ' ' . __('messages.availibility') }}',
                                content: el,
                                icon: 'warning',
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-warning'
                                    }
                                }
                            });
                        } else {
                            const el = document.createElement('div')
                            el.innerHTML =
                                "Berhasil ditambahkan ke Cart. <a href='{{ route('my_cart') }}'>Lihat Cart</a>"
                            swal({
                                title: 'Berhasil',
                                content: el,
                                icon: 'success',
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-success'
                                    }
                                },
                            });
                            reloadCartItems(user_id);
                        }
                    } else if(res.data.is_success == false) {
                        const el = document.createElement('div')
                        el.innerHTML =
                            "Silahkan <a href='{{ route('register') }}'>Registrasi</a> sebagai Member atau <a href='{{ route('login') }}'>Login</a> dahulu untuk melanjutkan"
                        swal({
                            title: 'Registrasi Dahulu',
                            content: el,
                            icon: 'warning',
                            buttons: {
                                confirm: {
                                    className: 'btn btn-warning'
                                }
                            }
                        });
                    } else if(res.data.is_success == 'only_member') {
                        swal({
                            title: 'Member account only',
                            text: res.meta.message,
                            icon: 'warning',
                            buttons: {
                                confirm: {
                                    className: 'btn btn-warning'
                                }
                            }
                        });
                    }
                },
                error: function(res) {
                    // console.log(res);
                    swal({
                        title: 'Gagal',
                        text: res.meta.message,
                        icon: 'error',
                        buttons: {
                            confirm: {
                                className: 'btn btn-warning'
                            }
                        }
                    });
                }
            })
        });

        $("form#product_wishlist_form").submit(function(event) {
            event.preventDefault();
            var product_id = $("#formWishlistProductId").val();
            var the_data = {
                product_id: product_id,
                user_id: user_id,
            }
            $.ajax({
                type: "POST",
                url: "{{ route('v1.add_to_wishlist') }}",
                data: the_data,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    console.log(res)
                    if (res.data.is_success == true) {
                        if (res.data.wishlist_existed == true) {
                            const el = document.createElement('div')
                            el.innerHTML =
                                res.meta.message
                            swal({
                                title: 'Sudah Ada di Wishlist',
                                content: el,
                                icon: 'warning',
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-warning'
                                    }
                                }
                            });
                        } else {
                            const el = document.createElement('div')
                            el.innerHTML =
                                "Berhasil ditambahkan ke Wishlist. <a href='{{ route('my_wishlist') }}'>Lihat Wishlist</a>"
                            swal({
                                title: 'Berhasil',
                                content: el,
                                icon: 'success',
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-success'
                                    }
                                },
                            });
                            reloadCartItems(user_id);
                        }
                    } else if(res.data.is_success == false) {
                        const el = document.createElement('div')
                        el.innerHTML =
                            "Silahkan <a href='{{ route('register') }}'>Registrasi</a> sebagai Member atau <a href='{{ route('login') }}'>Login</a> dahulu untuk melanjutkan"
                        swal({
                            title: 'Registrasi Dahulu',
                            content: el,
                            icon: 'warning',
                            buttons: {
                                confirm: {
                                    className: 'btn btn-warning'
                                }
                            }
                        });
                    } else if(res.data.is_success == 'only_member') {
                        swal({
                            title: 'Member account only',
                            text: res.meta.message,
                            icon: 'warning',
                            buttons: {
                                confirm: {
                                    className: 'btn btn-warning'
                                }
                            }
                        });
                    }
                },
                error: function(res) {
                    // console.log(res);
                    swal({
                        title: 'Gagal',
                        text: res.meta.message,
                        icon: 'error',
                        buttons: {
                            confirm: {
                                className: 'btn btn-warning'
                            }
                        }
                    });
                }
            })
        });

        $(document).on('click', '.floating_delete_cart', function(e) {
            var cart_id = $(this).attr('id');
            var the_data = {
                cart_id: cart_id,
                user_id: user_id
            };
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "{{ route('v1.delete_cart_item') }}",
                data: the_data,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.data.is_success == true) {
                        var sessionMessage = res.meta.message;
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-bottom-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }

                        $(document).ready(function onDocumentReady() {
                            toastr.success(sessionMessage);
                        });
                        $('.floating-cart-item#' + cart_id).fadeOut();
                        reCalculateTotal(user_id)
                        reloadCartItems(user_id);
                    } else {
                        const el = document.createElement('div')
                        el.innerHTML =
                            "Silahkan <a href='{{ route('register') }}'>Registrasi</a> dahulu untuk melanjutkan"
                        swal({
                            title: 'Registrasi Dahulu',
                            content: el,
                            icon: 'warning',
                            buttons: {
                                confirm: {
                                    className: 'btn btn-warning'
                                }
                            }
                        });
                    }
                },
                error: function(res) {
                    console.log(res);
                    swal({
                        title: 'Gagal',
                        text: res.metamessage,
                        icon: 'error',
                        buttons: {
                            confirm: {
                                className: 'btn btn-warning'
                            }
                        }
                    });
                }
            })
        })
    })
</script>

@if (Session::has('success'))
    <script>
        var sessionMessage = "{{ Session::get('success') }}";
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        $(document).ready(function onDocumentReady() {
            toastr.success(sessionMessage);
        });
    </script>
@endif

@if (Session::has('error'))
    <script>
        var sessionMessage = "{{ Session::get('error') }}";
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        $(document).ready(function onDocumentReady() {
            toastr.error(sessionMessage);
        });
    </script>
@endif
</body>


</html>
