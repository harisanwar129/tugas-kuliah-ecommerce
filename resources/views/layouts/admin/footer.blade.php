        </div> <!-- end container-fluid -->
        </div> <!-- end content -->
        </div>

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        {{ config('setting.footer_text') }}
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->


        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="{{ asset('admin') }}/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="{{ asset('admin') }}/js/app.min.js"></script>

        <!-- Toastr js -->
        <script src="{{ asset('admin') }}/libs/toastr/toastr.min.js"></script>

        <script src="{{ asset('admin') }}/js/pages/toastr.init.js"></script>

        <script src="{{ asset('admin') }}/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>


        <!-- Sweet Alert -->
        <script src="{{ asset('admin/js/sweetalert/sweetalert.min.js') }}"></script>

        {{-- Rupiah JS --}}
        <script src="{{ asset('admin/js/autonumeric/autoNumeric.js') }}"></script>


        @if (isset($datatable))
            <!-- Datatable plugin js -->
            <script src="{{ asset('admin') }}/libs/datatables/jquery.dataTables.min.js"></script>
            <script src="{{ asset('admin') }}/libs/datatables/dataTables.bootstrap4.min.js"></script>

            <script src="{{ asset('admin') }}/libs/datatables/dataTables.responsive.min.js"></script>
            <script src="{{ asset('admin') }}/libs/datatables/responsive.bootstrap4.min.js"></script>

            <script src="{{ asset('admin') }}/libs/datatables/dataTables.buttons.min.js"></script>
            <script src="{{ asset('admin') }}/libs/datatables/buttons.bootstrap4.min.js"></script>

            <script src="{{ asset('admin') }}/libs/jszip/jszip.min.js"></script>
            <script src="{{ asset('admin') }}/libs/pdfmake/pdfmake.min.js"></script>
            <script src="{{ asset('admin') }}/libs/pdfmake/vfs_fonts.js"></script>

            <script src="{{ asset('admin') }}/libs/datatables/buttons.html5.min.js"></script>
            <script src="{{ asset('admin') }}/libs/datatables/buttons.print.min.js"></script>

            <script src="{{ asset('admin') }}/libs/datatables/dataTables.keyTable.min.js"></script>
            <script src="{{ asset('admin') }}/libs/datatables/dataTables.select.min.js"></script>

            <!-- Datatables init -->
            <script src="{{ asset('admin') }}/js/pages/datatables.init.js"></script>
        @endif

        <script>
            function string_to_slug(str) {
                str = str.replace(/^\s+|\s+$/g, ''); // trim
                str = str.toLowerCase();

                // remove accents, swap ñ for n, etc
                var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
                var to = "aaaaeeeeiiiioooouuuunc------";
                for (var i = 0, l = from.length; i < l; i++) {
                    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
                }

                str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                    .replace(/\s+/g, '-') // collapse whitespace and replace by -
                    .replace(/-+/g, '-'); // collapse dashes

                return str;
            }

            $(document).ready(function() {
                $(".myForm").on('submit', function(event) {
                    $(".formSubmitter").attr('disabled', true).addClass('disabled');
                    $(".myForm").attr('onsubmit', 'return false');
                });

                $(document).on('click', '.buttonDeletion', function(e) {
                    e.preventDefault();
                    let form = $(this).parents('form');

                    swal({
                        title: 'Apakah Anda yakin ingin menghapus data ini?',
                        text: "Data yang dihapus tidak bisa dikembalikan",
                        icon: 'warning',
                        buttons: {
                            confirm: {
                                text: 'Ya, Hapus!',
                                className: 'btn btn-success'
                            },
                            cancel: {
                                visible: true,
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

                $(document).on('click', '.buttonKirimPesanan', function(e) {
                    e.preventDefault();
                    let form = $(this).parents('form');

                    swal({
                        title: 'Apakah Anda yakin mengirim pesanan ini?',
                        text: "Status pengiriman akan diubah menjadi Dikirim",
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

                $('.rupiah').autoNumeric('init', {
                    aSign: '',
                    pSign: 'p',
                    aPad: false
                });
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

        @stack('js')


        </body>

        </html>
