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

    <title>Application Complete - HireSmart</title>

    <link rel="icon" type="image/png" sizes="56x56" href="{{ asset('images/fav-icon/icon.png') }}">
    <link href="{{ asset('css/responsive_template.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style_template.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        @keyframes slideDown {
            from { transform: translate(-50%, -100%); }
            to { transform: translate(-50%, 0); }
        }

        /* Add these new styles */
        .main-page-wrapper {
            display: none;
        }

        #loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            border-radius: 10px 10px 0 0;
        }

        .card-header.bg-success {
            background-color: #28a745 !important;
            color: white;
        }

        .card-body {
            padding: 30px;
            text-align: center;
        }

        .btn-primary {
            background: #006dd7;
            color: white;
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            border: none;
            cursor: pointer;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background: #0055a9;
        }

        .btn-outline-secondary {
            background: transparent;
            color: #6c757d;
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            border: 1px solid #6c757d;
            cursor: pointer;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-color: #bee5eb;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="main-page-wrapper">
        <!-- Loader -->
        <div id="loader-wrapper">
            <div id="loader">
                <ul>
                    <li></li><li></li><li></li>
                    <li></li><li></li><li></li>
                </ul>
            </div>
        </div>

        <!-- Header -->
        <header style="position: fixed; width: 100%; top: 0; left: 0; z-index: 1000; padding: 15px 0; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); transition: all 0.3s ease;">
            <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                <nav style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex-shrink: 0;">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="Logo" style="height: 40px; display: block;">
                        </a>
                    </div>
                    <ul style="display: flex; align-items: center; gap: 30px; margin: 0; padding: 0; list-style: none;">
                        <li>
                            <a href="{{ route('jobs.index') }}" 
                               style="background: #006dd7; color: white; padding: 10px 25px; border-radius: 5px; text-decoration: none; font-weight: 500; border: none;">
                                Back to Listings
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>

        <!-- Success Section -->
        <div class="container" style="margin-top: 120px; max-width: 800px; padding: 0 20px;">
            <div class="row justify-content-center">
                <div class="col-md-8" style="width: 100%;">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h4 style="margin-bottom: 0; color: white; font-size: 24px; text-align: center;">Application Complete</h4>
                        </div>
                        <div class="card-body">
                            <div style="margin-bottom: 25px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#28a745" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                </svg>
                            </div>
                            
                            <h3 style="margin-bottom: 20px; color: #333; font-size: 28px;">Thank you for your application!</h3>
                            
                            <p style="margin-bottom: 25px; color: #666; font-size: 18px; line-height: 1.5;">Your application and personality assessment have been successfully submitted.</p>
                            
                            <div style="background-color: #d1ecf1; color: #0c5460; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                                <p style="margin: 0; line-height: 1.5;">We will review your application and contact you if you are selected for the next stage of the hiring process.</p>
                            </div>
                            
                            <div style="margin-top: 30px;">
                                <a href="{{ route('jobs.index') }}" class="btn-primary" style="margin-right: 10px;">View More Job Opportunities</a>
                                <a href="{{ url('/') }}" class="btn-outline-secondary">Return to Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer style="background-color: #ffffff; padding: 50px 0 0; color: #333; margin-top: 50px; border-top: 1px solid #eee;">
            <div class="container">
                <div class="row" style="display: flex; justify-content: space-between; margin-bottom: 30px;">
                    <!-- Company Info -->
                    <div class="col-md-4">
                        <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="Logo" style="height: 50px; margin-bottom: 15px;">
                        <p style="color: #333; font-size: 14px; line-height: 1.6; margin-bottom: 10px;">
                            Street 9, Plot 67, Sector H-8/4, Islamabad, Pakistan
                        </p>
                        <p style="color: #006dd7; margin-bottom: 5px;">careers@szabist-isb.edu.pk</p>
                        <p style="color: #006dd7;">+92-51-4863363-65</p>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-md-4">
                        <h4 style="color: #333; margin-bottom: 20px;">Quick Links</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li style="margin-bottom: 10px;">
                                <a href="{{ route('home') }}" style="color: #333; text-decoration: none;">Home</a>
                            </li>
                            <li style="margin-bottom: 10px;">
                                <a href="#" style="color: #333; text-decoration: none;">About Us</a>
                            </li>
                            <li style="margin-bottom: 10px;">
                                <a href="#" style="color: #333; text-decoration: none;">Contact</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Subscribe Section -->
                    <div class="col-md-4">
                        <h4 style="color: #333; margin-bottom: 20px;">Subscribe Us</h4>
                        <div style="display: flex;">
                            <input type="email" placeholder="Enter your email" 
                                   style="padding: 10px; border: 1px solid #ddd; border-radius: 5px 0 0 5px; width: 70%;">
                            <button type="submit" 
                                    style="padding: 10px 20px; background: #006dd7; border: none; border-radius: 0 5px 5px 0; color: white; cursor: pointer;">
                                Subscribe
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bottom Footer -->
                <div style="border-top: 1px solid #eee; padding: 20px 0; text-align: center;">
                    <p style="margin: 0; color: #333; font-size: 14px;">
                        © {{ date('Y') }} HireSmart. All rights reserved
                    </p>
                </div>
            </div>
        </footer>
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

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            // Hide loader and show content when page is ready
            $('#loader-wrapper').fadeOut();
            $('.main-page-wrapper').fadeIn();
        });
    </script>
</body>
</html> 