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
                            <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="Logo" style="height: 40px; display: block;">
                        </a>
                    </div>

                    <ul style="display: flex; align-items: center; gap: 30px; margin: 0; padding: 0; list-style: none;">
                        <li>
                            <a href="{{ url('/') }}" 
                               style="background: #006dd7; color: white; padding: 10px 25px; border-radius: 5px; text-decoration: none; font-weight: 500; border: none;">
                                Back to Home
                            </a>
                        </li>
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
                @if($jobs->isEmpty())
                    <div class="col-md-12">
                        <p style="text-align: center; color: #666;">No job positions available at the moment.</p>
                    </div>
                @else
                    @foreach($jobs as $job)
                        <div class="col-md-6 col-sm-6 wow fadeInUp" style="margin-bottom: 30px;">
                            <div style="background: white; border-radius: 10px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease; height: 100%;">
                                <h3 style="color: #006dd7; margin-bottom: 15px; font-size: 24px;">{{ $job->title }}</h3>
                                
                                <div style="margin-bottom: 15px;">
                                    <p style="color: #666; margin-bottom: 10px;">
                                        <strong>Department:</strong> {{ $job->jobRequest->department->name ?? 'Department Not Specified' }}
                                    </p>
                                    <p style="color: #666; margin-bottom: 10px;">
                                        <strong>Description:</strong> {{ $job->description ?? 'No description available' }}
                                    </p>
                                    <p style="color: #666; margin-bottom: 10px;">
                                        <strong>Requirements:</strong> {{ $job->requirements ?? 'No requirements specified' }}
                                    </p>
                                    <p style="color: #666; margin-bottom: 10px;">
                                        <strong>Posted:</strong> {{ $job->posted_at ? \Carbon\Carbon::parse($job->posted_at)->format('M d, Y') : \Carbon\Carbon::parse($job->created_at)->format('M d, Y') }}
                                    </p>
                                </div>

                                <div style="display: flex; justify-content: center; margin-top: 20px;">
                                    <a href="{{ route('jobs.apply', $job->id) }}" 
                                       style="display: inline-block; padding: 12px 30px; background: #006dd7; color: white; text-decoration: none; border-radius: 5px; transition: all 0.3s; font-weight: 500; text-align: center;">
                                        Apply Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-md-12" style="text-align: center; margin-top: 40px;">
                    {{ $jobs->links() }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer style="background-color: #ffffff; padding: 60px 0 30px;">
            <div class="container">
                <div class="row">
                    <!-- Address Section -->
                    <div class="col-md-4 mb-4 mb-md-0">
                        <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="SZABIST" style="height: 50px; margin-bottom: 25px;">
                        <p style="color: #666; margin-bottom: 10px; font-size: 15px;">Street 9, Plot 67, Sector H-8/4</p>
                        <p style="color: #666; margin-bottom: 10px; font-size: 15px;">Islamabad, Pakistan</p>
                        <p style="color: #666; margin-bottom: 10px; font-size: 15px;">careers@szabist-isb.edu.pk</p>
                        <p style="color: #666; margin-bottom: 10px; font-size: 15px;">+92-51-4863363-65</p>
                    </div>

                    <!-- Quick Links Section -->
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h3 style="font-size: 24px; color: #333; margin-bottom: 25px;">Quick Links</h3>
                        <ul style="list-style: none; padding: 0;">
                            <li style="margin-bottom: 15px;">
                                <a href="#" style="color: #666; text-decoration: none; font-size: 15px;">How it Works</a>
                            </li>
                            <li style="margin-bottom: 15px;">
                                <a href="#" style="color: #666; text-decoration: none; font-size: 15px;">About Us</a>
                            </li>
                            <li style="margin-bottom: 15px;">
                                <a href="#" style="color: #666; text-decoration: none; font-size: 15px;">Contact</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Newsletter Section -->
                    <div class="col-md-4">
                        <h3 style="font-size: 24px; color: #333; margin-bottom: 25px;">Subscribe to Newsletter</h3>
                        <div>
                            <input type="email" placeholder="Enter your email" 
                                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 15px; margin-bottom: 10px;">
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div style="border-top: 1px solid #eee; margin-top: 40px; padding-top: 20px; text-align: center;">
                    <p style="color: #666; font-size: 14px; margin: 0;">© {{ date('Y') }} HireSmart. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Scroll Top Button -->
        <button class="scroll-top tran3s">
            <i class="fa fa-angle-up" aria-hidden="true"></i>
        </button>
    </div>

    <!-- Scripts -->
    <script>
        // Show loader immediately
        document.getElementById('loader-wrapper').style.display = 'flex';
        document.querySelector('.main-page-wrapper').style.display = 'none';

        // When DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');
            const mainContent = document.querySelector('.main-page-wrapper');
            if (mainContent) {
                mainContent.style.display = 'block';
                mainContent.style.opacity = '0';
            }
        });

        // When everything is loaded
        window.addEventListener('load', function() {
            console.log('Window Loaded');
            const loader = document.getElementById('loader-wrapper');
            const mainContent = document.querySelector('.main-page-wrapper');
            
            if (mainContent) {
                // Show the content
                mainContent.style.opacity = '1';
                
                // Hide the loader
                if (loader) {
                    setTimeout(function() {
                        loader.style.opacity = '0';
                        setTimeout(function() {
                            loader.style.display = 'none';
                        }, 300);
                    }, 500);
                }
            }
        });
    </script>

    <!-- Move jQuery to the top -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Other Scripts -->
    <script src="{{ asset('js/jquery.appear.js') }}"></script>
    <script src="{{ asset('js/jquery.countTo.js') }}"></script>
    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/wow.min.js') }}"></script>
    <script src="{{ asset('js/menu.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>