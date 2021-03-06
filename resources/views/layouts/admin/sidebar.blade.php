<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="{{ route('dashboard.index') }}">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                @if (Auth::user()->role == 'ADMIN')
                    <li>
                        <a href="{{ route('dashboard.master.user.index') }}">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            <span> Akun </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('dashboard.master.product_category.index') }}">
                            <i class="mdi mdi-tag"></i>
                            <span> Kategori </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.master.product.index') }}">
                            <i class="mdi mdi-content-copy"></i>
                            <span> Produk </span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role == 'SELLER')
                    <li>
                        <a href="{{ route('dashboard.my_shop') }}">
                            <i class="mdi mdi-home-currency-usd"></i>
                            <span> Toko Saya </span>
                        </a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('dashboard.histori_penjualan') }}">
                        <i class="mdi mdi-file-table-outline"></i>
                        <span> Transaksi Penjualan </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('dashboard.setting') }}">
                        <i class="mdi mdi-settings"></i>
                        <span> Pengaturan </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.master.slider.index') }}">
                        <i class="mdi mdi-settings"></i>
                        <span> Slider  </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.master.page.index') }}">
                        <i class="mdi mdi-settings"></i>
                        <span>  Halaman </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.master.menu.index') }}">
                        <i class="mdi mdi-settings"></i>
                        <span> Menu </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.master.submenu.index') }}">
                        <i class="mdi mdi-settings"></i>
                        <span> Sub Menu </span>
                    </a>
                </li>

                  <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item notify-item"  onClick="return confirm('Apakah Anda yakin ingin keluar?')"><i
                                    class="mdi mdi-settings"></i> Logout</button>
                        </form>

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
