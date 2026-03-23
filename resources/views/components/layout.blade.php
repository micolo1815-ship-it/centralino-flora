<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Centralino Flora</title>

    <link rel="icon" type="image/png" href="{{ asset('images/Logo/Logo.png') }}">

    <link rel="stylesheet" href="{{ asset('build/style.css') }}">

    <link rel="stylesheet" href="{{ asset('vendors/animate.css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/fancybox/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/slick-carousel/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/slick-carousel/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main-style.css') }}">

    <script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/loader.js') }}" defer></script>
</head>

<body>
    <div class="oleez-loader"></div>

    <header class="oleez-header">
        <!-- Desktop view -->
        <nav class="navbar navbar-expand-lg navbar-light navbar-shadow main-navbar">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/Logo/MCU-P-horizontal-1.png') }}" alt="McuLogo" draggable="false">
            </a>
            <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#oleezMainNav"
                aria-controls="oleezMainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="oleezMainNav">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                    <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item dropdown {{ request()->is('forestry*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/forest') }}">
                            Forest <span class="arrow">&#9662;</span>
                        </a>
                        <div class="dropdown-menu">
                            @foreach($locations as $location)
                                <a class="dropdown-item" href="{{ route('home.location', $location->id) }}">
                                    {{ $location->abbreviation ?? $location->name }}
                                </a>
                            @endforeach
                        </div>
                    </li>
                    <li class="nav-item {{ request()->is('about') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/abouts') }}">About</a>
                    </li>
                    <li class="nav-item {{ request()->is('contact') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/contact') }}">Contact</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Mobile view -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top scroll-navbar d-none wow fadeInDown">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/Logo/MCU-P-horizontal-1.png') }}" alt="McuLogo" draggable="false">
            </a>
            <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#oleezMainNav"
                aria-controls="oleezMainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="oleezMainNav">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                    <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item dropdown {{ request()->is('forestry*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/forestry') }}">
                            Forest <span class="arrow">&#9662;</span>
                        </a>
                        <div class="dropdown-menu">
                            @foreach($locations as $location)
                                <a class="dropdown-item" href="{{ route('home.location', $location->id) }}">
                                    {{ $location->abbreviation ?? $location->name }}
                                </a>
                            @endforeach
                        </div>
                    </li>
                    <li class="nav-item {{ request()->is('about') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/abouts') }}">About</a>
                    </li>
                    <li class="nav-item {{ request()->is('contact') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/contact') }}">Contact</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Content -->
    {{ $slot }}

    <!-- Footer -->
    <footer class="oleez-footer wow fadeInUp">
        <div class="container">
            <div class="footer-content">
                <div class="row">
                    <div class="col-md-6">
                        <a href="https://mcu.edu.ph/"><img src="{{ asset('images/MCU logo.png') }}" alt="McuLogo" class="footer-logo"></a>
                        <a href="https://www.facebook.com/mcu.cassco"><img src="{{ asset('images/CAS Logo.png') }}" alt="CAS" class="footer-logo"></a>
                        <a href="https://www.facebook.com/MCUBioSoc"><img src="{{ asset('images/Logo-Biology.PNG') }}" alt="Biology Logo" class="footer-logo"></a>
                        <p class="footer-intro-text">MCU | College of Arts and Sciences | Biology | Computer Sciences</p>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6 footer-widget-text">
                                <h6 class="widget-title">INQUIRIES</h6>
                                <p class="footer-social-links"><a href="mailto:mcu.cas.biosoc@gmail.com">mcu.cas.biosoc@gmail.com</a></p>
                            </div>
                            <div class="col-md-6 footer-widget-text">
                                <h6 class="widget-title">ADDRESS</h6>
                                <p class="footer-social-links">
                                    <a href="https://www.google.com/maps?ll=14.659,120.986248&z=15&t=m&hl=en&gl=US&mapclient=embed&cid=15158235345735323880">
                                        Manila Central University, Epifanio de los Santos Ave, Morning Breeze Subdivision, Caloocan, 1400 Metro Manila
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-text">
                <p class="mb-md-0">© 2024, BioSoc & CSIT</p>
                <p class="mb-0">All right reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JS -->
    <script src="{{ asset('vendors/popper.js/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/wowjs/wow.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/slick-carousel/slick.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/landing.js') }}"></script>
    <script src="{{ asset('vendors/fancybox/jquery.fancybox.min.js')}}"></script>
    <script>
        new WOW({ mobile: false }).init();
    </script>
</body>

</html>
