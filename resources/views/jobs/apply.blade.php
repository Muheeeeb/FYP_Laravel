<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2c2c2c">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Apply for Position - SZABIST</title>
    <link rel="icon" type="image/png" sizes="56x56" href="{{ asset('images/fav-icon/icon.png') }}">
    <link href="{{ asset('css/style_template.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive_template.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <div class="main-page-wrapper">
        <!-- Header -->
        <header style="position: fixed; width: 100%; top: 0; left: 0; z-index: 1000; padding: 15px 0; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);">
            <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                <nav style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex-shrink: 0;">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('images/logo/szabist-logo.jpeg') }}" alt="Logo" style="height: 40px;">
                        </a>
                    </div>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <div class="container" style="margin-top: 120px; padding: 40px 20px; max-width: 1000px; margin-left: auto; margin-right: auto;">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card" style="background: white; border-radius: 15px; padding: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                        <!-- Job Details Section -->
                        <div style="text-align: center; margin-bottom: 40px; padding-bottom: 30px; border-bottom: 1px solid #eee;">
                            <h2 style="font-size: 36px; color: #333; margin-bottom: 20px;">Junior Lecturer Position</h2>
                            <div style="display: flex; justify-content: center; gap: 40px; margin-bottom: 20px;">
                                <div>
                                    <p style="color: #666; font-size: 18px;"><strong>Department:</strong> Robotics and AI</p>
                                </div>
                                <div>
                                    <p style="color: #666; font-size: 18px;"><strong>Position:</strong> Junior Lecturer</p>
                                </div>
                            </div>
                            <p style="color: #666; font-size: 18px;">Lab attendant required</p>
                        </div>

                        <!-- Application Form -->
                        <div style="background: #f8f9fa; padding: 30px; border-radius: 10px;">
                            <h3 style="color: #333; font-size: 24px; margin-bottom: 30px; text-align: center;">Submit Your Application</h3>
                            
                            <form action="{{ route('jobs.submit-application', $job->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- Personal Information -->
                                <div style="margin-bottom: 30px;">
                                    <h4 style="color: #333; font-size: 20px; margin-bottom: 20px;">Personal Information</h4>
                                    <div class="row">
                                        <div class="col-md-6" style="margin-bottom: 20px;">
                                            <label style="display: block; margin-bottom: 8px; color: #333;">Full Name *</label>
                                            <input type="text" name="full_name" required 
                                                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                                        </div>
                                        <div class="col-md-6" style="margin-bottom: 20px;">
                                            <label style="display: block; margin-bottom: 8px; color: #333;">Email Address *</label>
                                            <input type="email" name="email" required 
                                                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Cover Letter -->
                                <div style="margin-bottom: 30px;">
                                    <h4 style="color: #333; font-size: 20px; margin-bottom: 20px;">Cover Letter</h4>
                                    <textarea name="cover_letter" rows="6" required
                                              style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;"
                                              placeholder="Tell us why you're interested in this position..."></textarea>
                                </div>

                                <!-- Resume Upload -->
                                <div style="margin-bottom: 30px;">
                                    <h4 style="color: #333; font-size: 20px; margin-bottom: 20px;">Resume/CV</h4>
                                    <input type="file" name="resume" required accept=".pdf,.doc,.docx"
                                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                                    <small style="display: block; margin-top: 8px; color: #666;">Accepted formats: PDF, DOC, DOCX</small>
                                </div>

                                <!-- Submit Buttons -->
                                <div style="display: flex; 
                                            flex-direction: column; 
                                            align-items: center; 
                                            margin-top: 40px; 
                                            gap: 15px;">
                                    <button type="submit" 
                                            style="background: #006dd7; 
                                                   color: white; 
                                                   padding: 15px 40px; 
                                                   border: none; 
                                                   border-radius: 5px; 
                                                   font-size: 16px; 
                                                   cursor: pointer;
                                                   width: 200px;">Submit</button>
                                
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        @include('partials.footer')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>