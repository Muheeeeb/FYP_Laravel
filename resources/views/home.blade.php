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

    <title>HireSmart &amp; AI Based Hiring Platform</title>

    <link rel="icon" type="image/png" sizes="56x56" href="{{ asset('images/fav-icon/icon.png') }}">
    <link href="{{ asset('css/responsive_template.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style_template.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

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

        <!-- Header - with clean white background -->
        <header style="position: fixed; width: 100%; top: 0; left: 0; z-index: 1000; padding: 15px 0; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                <nav style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex-shrink: 0;">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="Logo" style="height: 40px; display: block;">
                        </a>
                    </div>
                    <ul style="display: flex; align-items: center; gap: 30px; margin: 0; padding: 0; list-style: none;">
                        <li>
                            <a href="{{ url('/') }}" style="color: #333; text-decoration: none; font-weight: 500; font-size: 16px; transition: color 0.3s; padding: 5px 10px;">Home</a>
                        </li>
                        <li>
                            <a href="{{ route('login') }}" style="background: #006dd7; color: white; padding: 10px 25px; border-radius: 5px; text-decoration: none; transition: all 0.3s; font-weight: 500; border: none;">Login</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <div style="position: relative; height: 100vh; overflow: hidden; margin-top: 70px;">
            <!-- Background image -->
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
                background-image: url('{{ asset('images/back.png') }}');
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;">
                <!-- Semi-transparent overlay -->
                <div style="position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.5);">
                </div>
            </div>
            
            <!-- Content on top of overlay -->
            <div style="position: relative; z-index: 2; 
                max-width: 1200px; 
                margin: 0 auto; 
                padding: 0 20px; 
                text-align: center; 
                padding-top: 30vh;">
                
                <h1 style="font-family: 'Arial', sans-serif;
                    font-size: 48px; 
                    font-weight: 600; 
                    margin: 0 0 20px 0; 
                    color: white;
                    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                    Transform Hiring & Solutions
                </h1>
                
                <h2 style="font-family: 'Arial', sans-serif;
                    font-size: 28px;
                    font-weight: 400;
                    margin: 0 0 30px 0;
                    color: white;
                    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                    AI-Powered Onboarding
                </h2>
                
                <a href="{{ route('jobs.listings') }}" 
                    style="display: inline-block; 
                    padding: 12px 30px; 
                    background: #006dd7; 
                    color: white; 
                    text-decoration: none; 
                    border-radius: 6px; 
                    font-weight: 500; 
                    font-size: 16px;
                    transition: all 0.3s;">
                    Explore Now
                </a>
            </div>
        </div>

        <!-- What We Do Section -->
        <div class="what-we-do">
            <div class="container">
                <h3>We specialize in transforming recruitment with AI-powered solutions, streamlining the hiring process from job posting to onboarding.</h3>
                <h6>Our platform ensures accurate candidate matching, automated workflows &amp; data-driven decision-making to help organizations hire efficiently and intelligently.</h6>

                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12 wow fadeInLeft">
                        <div class="single-block">
                            <div class="icon color-one"><i class="flaticon-note"></i></div>
                            <h6>Tailored Role-Based Interfaces</h6>
                            <h5><a href="#" class="tran3s">Customized tools for each department &amp; Member</a></h5>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12 wow fadeInUp">
                        <div class="single-block">
                            <div class="icon color-two middle-block"><i class="flaticon-bar-chart2"></i></div>
                            <h6>Automated Onboarding Solutions</h6>
                            <h5><a href="#" class="tran3s">Seamless integration &amp; Reduced workload</a></h5>
                        </div>
                    </div>
                    <div class="col-md-4 hidden-sm col-xs-12 wow fadeInRight">
                        <div class="single-block">
                            <div class="icon color-three"><i class="flaticon-diamond"></i></div>
                            <h6>AI-Driven Recruitment</h6>
                            <h5><a href="#" class="tran3s">Smarter hiring &amp; Faster results</a></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- More About Us Section -->
        <div class="more-about-us bg-color">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-offset-5">
                        <div class="main-content">
                            <h2>Leading the Future of AI-Driven Recruitment in Asia &amp; and across other Marketing Domains.</h2>
                            <div class="main-wrapper">
                                <h4>Unmatched Innovation in Automated Recruitment</h4>
                                <p>We lead AI-driven recruitment in Asia with innovative, automated solutions. Our platform streamlines hiring through smart CV shortlisting, role-specific workflows, and seamless onboarding, reducing time-to-hire while ensuring accuracy &amp; Tailored for growth, we give organizations a competitive edge in the fast-paced market.</p>
                                <img src="{{ asset('images/home/sign.png') }}" alt="sign">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Section -->
        <div class="home-service-section">
            <div class="container">
                <div class="col-md-9 col-md-offset-3 main-container">
                    <div class="theme-title">
                        <h6>Our Services</h6>
                        <h2>We provide wide range of web &amp; <br>business services.</h2>
                        <p>We've strong work history with different business services</p>
                        <a href="#" class="tran3s">See All Services</a>
                    </div>
                    <ul class="clearfix row">
                        <li class="col-md-6">
                            <div>
                                <i class="flaticon-user"></i>
                                <h5><a href="#" class="tran3s">AI-Powered CV Shortlisting</a></h5>
                                <p>Automated, accurate candidate ranking in seconds.</p>
                            </div>
                        </li>
                        <li class="col-md-6">
                            <div>
                                <i class="flaticon-layers"></i>
                                <h5><a href="#" class="tran3s">Role-Specific Workflows</a></h5>
                                <p>Tailored interfaces for streamlined task management.</p>
                            </div>
                        </li>
                        <li class="col-md-6">
                            <div>
                                <i class="flaticon-bar-chart"></i>
                                <h5><a href="#" class="tran3s">Efficient Onboarding &amp; Automation</a></h5>
                                <p>Seamless integration for new hire processes.</p>
                            </div>
                        </li>
                        <li class="col-md-6">
                            <div>
                                <i class="flaticon-smartphone"></i>
                                <h5><a href="#" class="tran3s">Advanced Analytics and Reporting</a></h5>
                                <p>Data-driven insights for smarter recruitment decisions.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <img src="{{ asset('images/home/shape-2.png') }}" alt="Image" class="wow fadeInLeft">
            </div>
        </div>

        <!-- Testimonial Section -->
        <div class="two-section-wrapper">
            <div class="testimonial-section bg-image">
                <div class="container">
                    <div class="main-container col-md-6 col-md-offset-6">
                        <div class="theme-title">
                            <h6>Feedback</h6>
                            <h2>Check what's our Head <br>Say about HireSmart</h2>
                        </div>
                        <div class="testimonial-slider">
                            <div class="item">
                                <div class="wrapper">
                                    <p>The Principal of SZABIST commends the project for its innovative use of AI in recruitment, highlighting its potential to transform traditional hiring &amp; This solution exemplifies how technology can streamline processes and improve accuracy</p>
                                    <div class="name clearfix">
                                        <img src="{{ asset('images/101.png') }}" alt="">
                                        <h5>Mr. Khusro Pervaiz</h5>
                                        <span>Head of Campus</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-one">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="footer-logo">
                            <a href="{{ url('/') }}"><img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="Logo"></a>
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