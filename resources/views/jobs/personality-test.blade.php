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

    <title>Personality Assessment - HireSmart</title>

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

        .likert-scale .form-check {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .likert-scale .form-check-label {
            margin-top: 5px;
            font-size: 0.8rem;
            text-align: center;
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

        .card-header.bg-primary {
            background-color: #006dd7 !important;
            color: white;
        }

        .card-body {
            padding: 30px;
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
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background: #0055a9;
        }

        .form-check-input {
            cursor: pointer;
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

        @if(session('success'))
            <div style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); 
                        background-color: #d4edda; color: #155724; 
                        padding: 15px 30px; border-radius: 5px; z-index: 1000;
                        box-shadow: 0 2px 5px rgba(0,0,0,0.2); text-align: center;
                        animation: slideDown 0.5s ease-out;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Personality Assessment Section -->
        <div class="container" style="margin-top: 80px; max-width: 800px; padding: 0 20px;">
            <div class="row justify-content-center">
                <div class="col-md-10" style="width: 100%;">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h4 style="margin-bottom: 0; color: white; font-size: 24px;">Personality Assessment</h4>
                        </div>
                        <div class="card-body">
                            <div style="margin-bottom: 25px;">
                                <h5 style="color: #333; font-size: 18px; margin-bottom: 10px;">Job Application: {{ $application->jobPosting->title }}</h5>
                                <p style="color: #666; line-height: 1.5;">Please complete this personality assessment to help us understand your work style and preferences. Your answers will help us evaluate your fit for the role.</p>
                            </div>
                            
                            <form action="{{ route('jobs.personality-test.submit', $application->id) }}" method="POST">
                                @csrf
                                
                                @foreach($questions as $question)
                                    <div class="card" style="margin-bottom: 25px;">
                                        <div class="card-body" style="padding: 20px;">
                                            <h5 style="color: #333; font-size: 18px; margin-bottom: 15px;">{{ $question->question }}</h5>
                                            
                                            @if($question->type == 'likert_scale')
                                                <div class="likert-scale" style="margin: 15px 0;">
                                                    <div style="display: flex; text-align: center; justify-content: space-between;">
                                                        <div style="width: 20%;">
                                                            <div style="display: flex; flex-direction: column; align-items: center;">
                                                                <input type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_1" value="1" required style="margin-bottom: 5px;">
                                                                <label for="q{{ $question->id }}_1" style="font-size: 14px; text-align: center;">Strongly Disagree</label>
                                                            </div>
                                                        </div>
                                                        <div style="width: 20%;">
                                                            <div style="display: flex; flex-direction: column; align-items: center;">
                                                                <input type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_2" value="2" style="margin-bottom: 5px;">
                                                                <label for="q{{ $question->id }}_2" style="font-size: 14px; text-align: center;">Disagree</label>
                                                            </div>
                                                        </div>
                                                        <div style="width: 20%;">
                                                            <div style="display: flex; flex-direction: column; align-items: center;">
                                                                <input type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_3" value="3" style="margin-bottom: 5px;">
                                                                <label for="q{{ $question->id }}_3" style="font-size: 14px; text-align: center;">Neutral</label>
                                                            </div>
                                                        </div>
                                                        <div style="width: 20%;">
                                                            <div style="display: flex; flex-direction: column; align-items: center;">
                                                                <input type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_4" value="4" style="margin-bottom: 5px;">
                                                                <label for="q{{ $question->id }}_4" style="font-size: 14px; text-align: center;">Agree</label>
                                                            </div>
                                                        </div>
                                                        <div style="width: 20%;">
                                                            <div style="display: flex; flex-direction: column; align-items: center;">
                                                                <input type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_5" value="5" style="margin-bottom: 5px;">
                                                                <label for="q{{ $question->id }}_5" style="font-size: 14px; text-align: center;">Strongly Agree</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($question->type == 'multiple_choice')
                                                <div style="margin: 15px 0;">
                                                    @foreach(json_decode($question->options) as $index => $option)
                                                        <div style="margin-bottom: 10px;">
                                                            <input type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_{{ $index }}" value="{{ $option }}" required style="margin-right: 10px;">
                                                            <label for="q{{ $question->id }}_{{ $index }}" style="font-size: 16px;">
                                                                {{ $option }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                
                                <div style="text-align: center; margin-top: 30px;">
                                    <button type="submit" class="btn-primary" style="padding: 12px 40px; width: 100%;">
                                        Submit Assessment
                                    </button>
                                </div>
                            </form>
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

        // Remove success message after 5 seconds
        setTimeout(function() {
            const successMessage = document.querySelector('[style*="background-color: #d4edda"]');
            if (successMessage) {
                successMessage.style.opacity = '0';
                successMessage.style.transition = 'opacity 0.5s ease-out';
                setTimeout(() => successMessage.remove(), 500);
            }
        }, 5000);
    </script>
</body>
</html> 