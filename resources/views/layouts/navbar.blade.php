<!DOCTYPE html>
<html>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/dashboard') }}">
                    <img src="{{ asset('assets/img/kasir.png') }}" alt="" width="30">
                    {{ config('app.name', 'Store') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item"></li>
                        @else
                            <li class="nav-link"><a class="dropdown-item" href="{{ route('transaksi') }}">{{ __('Transaksi') }}</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('Laporan') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                    <li><a class="dropdown-item" href="{{ route('laporan.produk') }}">{{ __('Laporan Produk') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('laporan.penjualan') }}">{{ __('Laporan Penjualan') }}</a></li>
                                </ul>
                            </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ __('Management') }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                        <li><a class="dropdown-item" href="{{ route('management.produk') }}">{{ __('Management Produk') }}</a></li>
                                        <li><a class="dropdown-item" href="{{ route('management.kategori') }}">{{ __('Management Kategori') }}</a></li>
                                        <li><a class="dropdown-item" href="{{ route('management.member') }}">{{ __('Management Member') }}</a></li>
                                        @if (auth()->user()->role == 'Admin')
                                        <li><a class="dropdown-item" href="{{ route('management.petugas') }}">{{ __('Management Petugas') }}</a></li>
                                        @endif
                                    </ul>
                                </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ auth()->user()->name }}
                                </a>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                                    <li>
                                        <a class="nav-link" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                    </li>
                                </ul>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</body>

</html>
