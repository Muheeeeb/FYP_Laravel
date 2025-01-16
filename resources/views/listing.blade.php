<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- For IE -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- For Resposive Device -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- For Window Tab Color -->
    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#2c2c2c">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#2c2c2c">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#2c2c2c">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Job Listings - HireSmart</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="56x56" href="{{ asset('images/fav-icon/icon.png') }}">
    <link href="{{ asset('css/responsive_template.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style_template.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <div class="main-page-wrapper">
        <!-- Loader -->
        <div id="loader-wrapper">
            <div id="loader">
                <ul>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>
        </div>

        <!-- Header -->
        <header style="position: fixed; width: 100%; top: 0; left: 0; z-index: 1000; padding: 15px 0; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); transition: all 0.3s ease;">
            <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                <nav style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex-shrink: 0;">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" style="height: 40px; display: block;">
                        </a>
                    </div>

                    <ul style="display: flex; align-items: center; gap: 30px; margin: 0; padding: 0; list-style: none;">
                        <li>
                            <a href="{{ url('/') }}" style="color: white; text-decoration: none; font-weight: 500; font-size: 16px; transition: color 0.3s; padding: 5px 10px;">Home</a>
                        </li>
                        @guest
                        <li>
                            <a href="{{ route('login') }}" style="background: #006dd7; color: white; padding: 10px 25px; border-radius: 5px; text-decoration: none; transition: all 0.3s; font-weight: 500; border: none;">Login</a>
                        </li>
                        @else
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="background: #006dd7; color: white; padding: 10px 25px; border-radius: 5px; text-decoration: none; transition: all 0.3s; font-weight: 500; border: none;">Logout</a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        @endguest
                    </ul>
                </nav>
            </div>
        </header>

        <!-- Job Listings Section -->
        <div class="container" style="margin-top: 120px; padding: 40px 20px;">
            <div class="row">
                <div class="col-md-12">
                    <h2 style="text-align: center; color: #006dd7; margin-bottom: 40px; font-size: 36px;">Available Positions</h2>
                </div>
            </div>

            <div class="row">
                @foreach($jobs as $job)
                <div class="col-md-6 col-sm-6 wow fadeInUp" style="margin-bottom: 30px;">
                    <div style="background: white; border-radius: 10px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
                        <h3 style="color: #006dd7; margin-bottom: 15px; font-size: 24px;">{{ $job->title }}</h3>
                        <div style="margin-bottom: 15px;">
                            <span style="color: #666; font-size: 14px;">
                                <i class="flaticon-layers"></i> Department: {{ $job->department->name }}
                            </span>
                            <span style="color: #666; font-size: 14px; margin-left: 15px;">
                                <i class="flaticon-clock"></i> Posted: {{ $job->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p style="color: #666; margin-bottom: 20px; line-height: 1.6;">
                            {{ Str::limit($job->description, 150) }}
                        </p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <a href="{{ route('jobs.show', $job->id) }}" 
                               style="display: inline-block; padding: 10px 25px; background: #006dd7; color: white; text-decoration: none; border-radius: 5px; transition: all 0.3s; font-weight: 500;">
                                View Details & Apply
                            </a>
                            <span style="color: #006dd7; font-weight: 500;">
                                {{ $job->location }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-md-12" style="text-align: center; margin-top: 40px;">
                    {{ $jobs->links() }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-one">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="footer-logo">
                            <a href="index.html"><img src="{{ asset('images/logo/logo.png') }}" alt="Logo"></a>
                            <h5><a href="#" class="tran3s">Street 9, Plot 67, Sector H-8/4, Islamabad, Pakistan</a></h5>
                            <h6 class="p-color">careers@szabist-isb.edu.pk</h6>
                            <h6 class="p-color">+92-51-4863363-65</h6>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 footer-list">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="#" class="tran3s">How it Works</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 footer-subscribe">
                        <h4>Subscribe Us</h4>
                        <form action="#">
                            <input type="text" placeholder="Enter your mail">
                        </form>
                    </div>
                </div>
                <div class="bottom-footer clearfix">
                    <p class="float-left">&copy; 2023 <a href="#" class="tran3s p-color">HireSmart</a>. All rights reserved</p>
                </div>
            </div>
        </footer>

        <!-- Scroll Top Button -->
        <button class="scroll-top tran3s">
            <i class="fa fa-angle-up" aria-hidden="true"></i>
        </button>
    </div>

    <!-- Include all scripts -->
    <script src="{{ asset('js/jquery.appear.js') }}"></script>
    <script src="{{ asset('js/jquery.countTo.js') }}"></script>
    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="{{ asset('js/jquery.2.2.3.min.js') }}"></script>
    <script src="{{ asset('js/jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/wow.min.js') }}"></script>
    <script src="{{ asset('js/menu.js') }}"></script>
    <script src="{{ asset('js/jquery.mobile.customized.min.js') }}"></script>
    <script src="{{ asset('js/jquery.easing.1.3.js') }}"></script>
    <script src="{{ asset('js/camera.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>