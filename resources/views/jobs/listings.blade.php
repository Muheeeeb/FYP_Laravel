<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2c2c2c">
    <meta name="msapplication-navbutton-color" content="#2c2c2c">
    <meta name="apple-mobile-web-app-status-bar-style" content="#2c2c2c">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Job Listings - HireSmart</title>

    <link rel="icon" type="image/png" sizes="56x56" href="{{ asset('images/fav-icon/icon.png') }}">
    <link href="{{ asset('css/responsive_template.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style_template.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        .no-jobs-container {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin: 40px auto;
            max-width: 600px;
        }

        .no-jobs-icon {
            font-size: 60px;
            color: #006dd7;
            margin-bottom: 20px;
        }

        .no-jobs-title {
            color: #333;
            font-size: 28px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .no-jobs-message {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .back-home-btn {
            display: inline-block;
            padding: 12px 30px;
            background: #006dd7;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .back-home-btn:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .job-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            height: 100%;
        }

        .job-card:hover {
            transform: translateY(-5px);
        }
    </style>
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
        <header style="position: fixed; width: 100%; top: 0; left: 0; z-index: 1000; padding: 15px 0; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);">
            <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                <nav style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex-shrink: 0;">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="Logo" style="height: 40px;">
                        </a>
                    </div>
                    <ul style="display: flex; align-items: center; gap: 30px; margin: 0; padding: 0; list-style: none;">
                        <li>
                            <a href="{{ url('/') }}" style="color: white; text-decoration: none; font-weight: 500; font-size: 16px;">Home</a>
                        </li>
                        @guest
                            <li>
                                <a href="{{ route('login') }}" style="background: #006dd7; color: white; padding: 10px 25px; border-radius: 5px; text-decoration: none;">Login</a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                                   style="background: #006dd7; color: white; padding: 10px 25px; border-radius: 5px; text-decoration: none;">Logout</a>
                            </li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endguest
                    </ul>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <div class="container" style="margin-top: 120px; padding: 40px 20px;">
            <div class="row">
                <div class="col-md-12">
                    <h2 style="text-align: center; color: #006dd7; margin-bottom: 40px; font-size: 36px;">Available Positions</h2>
                </div>
            </div>

            <!-- Job Listings or No Jobs Message -->
            <div class="row">
                @if(count($jobs) > 0)
                    @foreach($jobs as $job)
                        <div class="col-md-6 col-sm-6 wow fadeInUp" style="margin-bottom: 30px">
                            <div class="job-opening" style="background: white; 
                                                          border-radius: 10px; 
                                                          padding: 40px; 
                                                          box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
                                                          height: 100%;
                                                          text-align: center;">
                                
                                <h4 style="font-size: 32px; 
                                          color: #333; 
                                          margin-bottom: 20px;
                                          font-weight: 500;">Junior Lecturer</h4>
                                
                                <p style="color: #666; 
                                         margin-bottom: 15px; 
                                         font-size: 17px;
                                         font-weight: normal;">
                                    Department: Robotics and AI
                                </p>
                                
                                <p style="color: #666; 
                                         margin-bottom: 15px; 
                                         font-size: 17px;
                                         font-weight: normal;">
                                    Position: Junior Lecturer
                                </p>
                                
                                <p style="color: #666; 
                                         margin-bottom: 30px; 
                                         font-size: 17px;
                                         font-weight: normal;">
                                    Lab attendant required
                                </p>

                                <div style="text-align: center;">
                                    <a href="{{ route('jobs.apply', $job->id) }}" 
                                       class="btn btn-primary" 
                                       style="background: #006dd7; 
                                              color: white; 
                                              padding: 12px 40px; 
                                              border-radius: 5px; 
                                              text-decoration: none;
                                              display: inline-block;
                                              font-size: 16px;
                                              font-weight: 500;">Apply Now</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <h3>No job listings available at the moment.</h3>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        @include('partials.footer')

        <!-- Scroll Top Button -->
        <button class="scroll-top tran3s">
            <i class="fa fa-angle-up" aria-hidden="true"></i>
        </button>
    </div>

    <!-- Scripts -->
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